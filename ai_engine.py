import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sqlalchemy import create_engine, text
from sklearn.cluster import KMeans
from sklearn.ensemble import IsolationForest
from sklearn.preprocessing import StandardScaler
import time
import sys
import math

# --- CONFIG & LOGGING ---
def thinking_log(message):
    sys.stdout.write(f"\r🧠 AI: {message}" + " " * 30)
    sys.stdout.flush()
    time.sleep(0.05)

def process_log(step, message):
    print(f"\n   [Bước {step}] ➤ {message}")
    time.sleep(0.2)

db_connection_str = 'mysql+pymysql://root:@127.0.0.1/elearning'
db_connection = create_engine(db_connection_str)

print("\n" + "="*60)
print("   🚀 AI ENGINE v3.2: FINAL LOGIC FIX")
print("="*60 + "\n")

# --- PHASE 1: DATA MINING ---
thinking_log("Đang quét lộ trình học tập...")

query = """
SELECT 
    u.id as user_id,
    u.name,
    (SELECT COUNT(*) FROM learning_logs WHERE user_id = u.id) as login_count,
    COALESCE((SELECT AVG(score) FROM lesson_submissions WHERE user_id = u.id), 0) as avg_score,
    (SELECT COUNT(*) FROM lesson_submissions WHERE user_id = u.id AND score IS NOT NULL) as scored_lessons_count,
    
    (
        SELECT COUNT(l.id)
        FROM lessons l
        JOIN chapters c ON l.chapter_id = c.id
        JOIN enrollments e2 ON c.course_id = e2.course_id
        WHERE e2.user_id = u.id
    ) as total_assigned,
    
    (SELECT COUNT(*) FROM lesson_submissions WHERE user_id = u.id AND status = 'completed') as completed_lessons,
    
    DATEDIFF(NOW(), (SELECT MAX(created_at) FROM learning_logs WHERE user_id = u.id)) as days_since_last_login,

    -- TÌM BÀI TIẾP THEO
    (
        SELECT CONCAT(l.title, ' (Môn: ', co.title, ')')
        FROM lessons l
        JOIN chapters c ON l.chapter_id = c.id
        JOIN courses co ON c.course_id = co.id
        JOIN enrollments e3 ON co.id = e3.course_id
        WHERE e3.user_id = u.id
        AND l.id NOT IN (SELECT lesson_id FROM lesson_submissions WHERE user_id = u.id AND status = 'completed')
        ORDER BY l.id ASC
        LIMIT 1
    ) as next_lesson_title

FROM users u
WHERE u.role = 'student'
"""

df = pd.read_sql(query, db_connection)
df = df.fillna(0)
df['next_lesson_title'] = df['next_lesson_title'].replace(0, "Không còn bài mới")

# Feature Engineering
df['progress_pct'] = df.apply(lambda x: min(100, (x['completed_lessons']/x['total_assigned']*100)) if x['total_assigned']>0 else 0, axis=1)
df['days_since_last_login'] = df['days_since_last_login'].fillna(365)
df['recency_score'] = df['days_since_last_login'].apply(lambda x: 100 if x <= 2 else (max(0, 100 - x*2)))
max_login = df['login_count'].max() if df['login_count'].max() > 0 else 1
df['effort_score'] = (df['login_count'] / max_login * 50) + (df['progress_pct'] / 100 * 50)

process_log(1, f"Đã tải dữ liệu của {len(df)} sinh viên.")

# --- PHASE 2: CLUSTERING ---
thinking_log("Đang phân loại năng lực...")
features = ['avg_score', 'progress_pct', 'effort_score', 'recency_score']
X = df[features]
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

kmeans = KMeans(n_clusters=4, random_state=42)
df['cluster'] = kmeans.fit_predict(X_scaled)
iso_forest = IsolationForest(contamination=0.05, random_state=42)
df['is_anomaly'] = iso_forest.fit_predict(X_scaled)

# --- PHASE 3: THE MENTOR LOGIC (FIXED) ---
thinking_log("Đang tính toán chiến lược (Đã sửa lỗi Logic)...")

def prescribe_strategy(row):
    current_gpa = row['avg_score']
    n_lessons = row['scored_lessons_count']
    next_lesson = row['next_lesson_title']
    recency = row['recency_score']

    # 1. Check Anomalies / Dropout first
    if recency < 20:
        return "BÓNG MA HỌC ĐƯỜNG", "High", "CẢNH BÁO: Bạn đã biến mất quá lâu. Hãy đăng nhập lại ngay!"
    if row['is_anomaly'] == -1:
        return "CẦN KIỂM TRA", "High", "Hành vi học tập bất thường. Vui lòng liên hệ giảng viên."

    # 2. XỬ LÝ TRƯỜNG HỢP HẾT BÀI (QUAN TRỌNG: FIX LỖI Ở ĐÂY)
    if next_lesson == "Không còn bài mới":
        if current_gpa < 5.0:
            return "CẢNH BÁO RỚT MÔN", "High", f"Bạn đã hoàn thành hết bài tập nhưng điểm trung bình chỉ đạt {current_gpa:.1f} (< 5.0). Vui lòng đăng ký học lại."
        elif current_gpa < 8.0:
            return "HỌC VIÊN TRUNG BÌNH", "Medium", f"Chúc mừng bạn đã hoàn thành khóa học với GPA {current_gpa:.1f}. Kết quả mức Khá."
        else:
            return "CHIẾN THẦN HỌC TẬP", "Low", f"Xuất sắc! Bạn đã tốt nghiệp khóa học với số điểm ấn tượng: {current_gpa:.1f}."

    # 3. Tính toán mục tiêu cho người CÒN bài học
    # --- NHÓM YẾU ---
    if current_gpa < 5.0:
        target_gpa = 5.0
        needed_score = (target_gpa * (n_lessons + 1)) - (current_gpa * n_lessons)
        needed_score = math.ceil(needed_score * 10) / 10
        
        if needed_score > 10:
            return "NGUY CƠ RẤT CAO", "High", f"Khó cứu vãn! Bạn cần hơn 10 điểm ở bài **'{next_lesson}'** để qua môn. Hãy gặp giảng viên ngay."
        elif needed_score <= 0:
             return "CẦN CÙ BÙ THÔNG MINH", "Medium", f"Cố lên! Chỉ cần hoàn thành bài **'{next_lesson}'** là kéo lại được điểm số."
        else:
            return "CẢNH BÁO RỚT MÔN", "High", f"Mục tiêu sống còn: Phải đạt tối thiểu **{needed_score} điểm** ở bài **'{next_lesson}'** để vào vùng an toàn."

    # --- NHÓM KHÁ ---
    elif current_gpa < 8.0:
        target_gpa = 8.0
        needed_score = (target_gpa * (n_lessons + 1)) - (current_gpa * n_lessons)
        needed_score = math.ceil(needed_score * 10) / 10

        if needed_score > 10:
            return "HẠT GIỐNG TIỀM NĂNG", "Medium", f"Cần nỗ lực rất lớn để lên loại Giỏi. Hãy cố hết sức ở bài **'{next_lesson}'**."
        elif needed_score <= 0:
            return "TIỀM NĂNG LỚN", "Low", f"Phong độ ổn định. Hãy hoàn thành tốt bài **'{next_lesson}'**."
        else:
            return "TIỀM NĂNG LỚN", "Low", f"Thử thách: Đạt **{needed_score} điểm** bài **'{next_lesson}'** để thăng hạng lên Giỏi (8.0)."

    # --- NHÓM GIỎI ---
    else:
        return "CHIẾN THẦN HỌC TẬP", "Low", f"Đẳng cấp! Hãy chinh phục bài **'{next_lesson}'** để giữ vững vị trí dẫn đầu."

results = df.apply(prescribe_strategy, axis=1, result_type='expand')
df[['persona', 'risk_level', 'recommendation']] = results

process_log(3, "Đã tính toán xong chiến lược học tập.")

# --- PHASE 4: SAVE ---
thinking_log("Đang lưu kết quả vào Database...")

# (Phần vẽ biểu đồ giữ nguyên, lược bỏ cho gọn)
plt.figure(figsize=(12, 7))
sns.set_style("whitegrid")
sns.scatterplot(data=df, x='effort_score', y='avg_score', hue='persona', style='risk_level', size='progress_pct', sizes=(50, 400), palette='viridis', alpha=0.9, edgecolor='black')
plt.title('BẢN ĐỒ CHIẾN LƯỢC HỌC TẬP (AI MENTOR FIXED)', fontsize=16, fontweight='bold')
plt.xlabel('Nỗ lực'), plt.ylabel('GPA')
plt.savefig('public/ai_analysis_chart.png', dpi=100)

with db_connection.connect() as conn:
    conn.execute(text("TRUNCATE TABLE student_predictions"))
    conn.commit()

output_data = df[['user_id', 'avg_score', 'login_count', 'progress_pct', 'risk_level', 'recommendation']]
output_data.columns = ['user_id', 'avg_score', 'login_count', 'completion_rate', 'risk_level', 'ai_recommendation']
output_data.to_sql('student_predictions', db_connection, if_exists='append', index=False)

print("\n" + "="*60)
print(f"✅ AI ĐÃ HOÀN TẤT. Logic 'Hết bài' đã được xử lý.")
print("="*60 + "\n")