<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AutoBotSeeder extends Seeder
{
    public function run()
    {
        // Tắt giới hạn bộ nhớ và thời gian để chạy dữ liệu lớn
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $this->command->info('🚀 KHỞI ĐỘNG AUTO-BOT V5 (BIG DATA EDITION)...');

        // 1. TẠO HỆ THỐNG TRƯỜNG LỚP
        $this->command->info('🏫 Đang xây dựng cơ sở vật chất (Ngành & Lớp)...');
        // Trả về mảng mapping: ['CNTT' => [id_lop_1, id_lop_2], 'KT' => [...]]
        $classMap = $this->createMajorsAndClassrooms();

        // 2. NHẬP USER (Hỗ trợ Tiếng Việt & Bỏ qua dòng lỗi)
        $this->command->info('📂 Đang tuyển sinh từ file CSV...');
        $this->importUsersFromCSV($classMap);

        // 3. TẠO ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin System', 'password' => Hash::make('12345678'), 'role' => 'admin', 'email_verified_at' => now()]
        );

        // 4. SOẠN GIÁO ÁN (TẠO KHÓA HỌC THEO CHUYÊN NGÀNH)
        $this->command->info('📚 Đang soạn giáo án và mở lớp học (Dữ liệu lớn)...');
        $instructors = User::whereIn('role', ['admin', 'instructor'])->get();
        if ($instructors->count() == 0) $instructors = collect([$admin]);
        
        // Tạo khóa học dựa trên chuyên ngành của lớp
        $courses = $this->createCurriculum($instructors, $classMap);

        // 5. MÔ PHỎNG HỌC TẬP & THI CỬ
        $students = User::where('role', 'student')->get();
        if ($students->count() == 0) {
            $this->command->warn('⚠️ Không có sinh viên. Tự tạo 50 sinh viên mẫu.');
            // Flatten danh sách ID lớp để random cho sinh viên fake
            $allClassIds = array_merge(...array_values($classMap));
            $students = $this->createFakeStudents(50, $allClassIds);
        }

        $totalStudents = $students->count();
        $this->command->info("🤖 Bot bắt đầu cho $totalStudents sinh viên đi học (Giả lập History chi tiết)...");
        $this->command->getOutput()->progressStart($totalStudents);

        foreach ($students->chunk(20) as $chunk) {
            foreach ($chunk as $student) {
                // Phân loại học lực
                $rand = rand(1, 100);
                $type = 'average'; // 50%
                if ($rand <= 20) $type = 'excellent'; // 20% Giỏi
                if ($rand > 70) $type = 'at_risk';    // 30% Yếu/Lười
                if ($rand > 95) $type = 'dropout';    // 5% Bỏ học (Đăng ký nhưng không học)

                // 1. Tạo Log đăng nhập
                $this->generateLoginLogs($student->id, $type);

                // 2. Học các môn của lớp mình
                $myCourses = collect($courses)->where('classroom_id', $student->classroom_id);
                
                foreach ($myCourses as $course) {
                    $this->simulateLearningProcess($student->id, $course->id, $type);
                }
            }
            $this->command->getOutput()->progressAdvance($chunk->count());
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('✅ HOÀN TẤT! Dữ liệu đã rất nhiều và chuẩn.');
    }

    // =========================================================================
    // PHẦN 1: CẤU TRÚC TRƯỜNG LỚP & USER
    // =========================================================================

    private function createMajorsAndClassrooms()
    {
        // Định nghĩa ngành và mã ngành
        $structure = [
            'Công nghệ thông tin' => ['CNTT', 2], // Tên ngành, Mã, Số lớp
            'Quản trị kinh doanh' => ['QTKD', 2],
            'Ngôn ngữ Anh' => ['NNA', 1],
            'Thiết kế đồ họa' => ['TKDH', 1]
        ];

        $map = []; // Lưu trữ ID lớp theo ngành để dùng sau

        foreach ($structure as $mName => $info) {
            $slug = Str::slug($mName);
            $majorId = DB::table('majors')->insertGetId([
                'name' => $mName, 'slug' => $slug, 
                'created_at' => now(), 'updated_at' => now()
            ]);

            $map[$info[0]] = []; // Khởi tạo mảng chứa ID lớp cho mã ngành này

            for ($i = 1; $i <= $info[1]; $i++) {
                $code = $info[0] . '_K18_' . $i; // VD: CNTT_K18_1
                
                $cId = DB::table('classrooms')->insertGetId([
                    'name' => "Lớp $mName $i", 
                    'code' => $code, 
                    'major_id' => $majorId, 
                    'created_at' => now(), 'updated_at' => now()
                ]);
                
                $map[$info[0]][] = $cId; // Lưu ID lớp
            }
        }
        return $map;
    }

    private function importUsersFromCSV($classMap)
    {
        $filePath = database_path('data/users.csv');
        if (!file_exists($filePath)) return;

        // Xử lý BOM & Đọc file
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (count($lines) > 0 && substr($lines[0], 0, 3) === "\xEF\xBB\xBF") $lines[0] = substr($lines[0], 3);

        $usersToInsert = [];
        $pass = Hash::make('12345678');
        $allClassIds = array_merge(...array_values($classMap)); // Gộp tất cả ID lớp lại

        foreach ($lines as $index => $line) {
            if ($index === 0) continue; // Bỏ header
            $row = str_getcsv($line);
            if (empty($row[1])) continue;

            $email = trim($row[1]);
            if (DB::table('users')->where('email', $email)->exists()) continue;

            $name = trim($row[0]);
            $roleRaw = $row[3] ?? 'Sinh viên';
            $role = 'student';
            $classroomId = null;

            if (stripos($roleRaw, 'Giảng viên') !== false) {
                $role = 'instructor';
            } elseif (stripos($roleRaw, 'Admin') !== false) {
                $role = 'admin';
            } else {
                // Random lớp cho sinh viên
                $classroomId = $allClassIds[array_rand($allClassIds)];
            }

            $usersToInsert[] = [
                'name' => $name, 'email' => $email, 'password' => $pass,
                'role' => $role, 'classroom_id' => $classroomId,
                'created_at' => now(), 'updated_at' => now()
            ];

            if (count($usersToInsert) >= 200) {
                DB::table('users')->insert($usersToInsert);
                $usersToInsert = [];
            }
        }
        if (!empty($usersToInsert)) DB::table('users')->insert($usersToInsert);
    }

    // =========================================================================
    // PHẦN 2: TẠO KHÓA HỌC PHONG PHÚ (THEO NGÀNH)
    // =========================================================================

    private function createCurriculum($instructors, $classMap)
    {
        // Định nghĩa môn học theo mã ngành (để dữ liệu chuẩn logic)
        $curriculum = [
            'CNTT' => [
                'Lập trình Web Fullstack (Laravel & React)',
                'Cấu trúc dữ liệu và giải thuật',
                'Trí tuệ nhân tạo (AI) căn bản',
                'Phát triển ứng dụng di động (Flutter)',
                'Cơ sở dữ liệu nâng cao'
            ],
            'QTKD' => [
                'Nguyên lý Marketing',
                'Quản trị nhân lực',
                'Kinh tế vi mô',
                'Kỹ năng đàm phán thương mại',
                'Digital Marketing thực chiến'
            ],
            'NNA' => [
                'Tiếng Anh giao tiếp nâng cao',
                'Kỹ năng biên phiên dịch',
                'Văn hóa Anh-Mỹ'
            ],
            'TKDH' => [
                'Nguyên lý thị giác',
                'Thiết kế UI/UX App Mobile',
                'Adobe Photoshop & Illustrator'
            ]
        ];

        $createdCourses = [];

        foreach ($classMap as $majorCode => $classIds) {
            // Lấy danh sách môn của ngành đó, nếu không có lấy mặc định
            $subjects = $curriculum[$majorCode] ?? ['Kỹ năng mềm', 'Tin học đại cương'];

            foreach ($classIds as $classId) {
                // Mỗi lớp sẽ được gán TẤT CẢ các môn chuyên ngành (Dữ liệu nhiều)
                foreach ($subjects as $subjectName) {
                    $instructor = $instructors->random();
                    
                    // Tạo Course
                    $courseId = DB::table('courses')->insertGetId([
                        'title' => "$subjectName - Lớp $classId",
                        'slug' => Str::slug($subjectName . '-' . $classId . '-' . Str::random(5)),
                        'code' => $majorCode . '_' . strtoupper(Str::random(3)) . '_' . $classId,
                        'description' => "Học phần $subjectName dành riêng cho sinh viên lớp $classId.",
                        'status' => 'open',
                        'user_id' => $instructor->id,
                        'classroom_id' => $classId,
                        'created_at' => now(), 'updated_at' => now()
                    ]);

                    // Tạo Nội dung (Nhiều chương, nhiều bài)
                    $this->generateCourseContent($courseId);

                    $createdCourses[] = (object)['id' => $courseId, 'classroom_id' => $classId];
                }
            }
        }
        return $createdCourses;
    }

    private function generateCourseContent($courseId)
    {
        // Tạo 3-5 Chương cho mỗi khóa
        $numChapters = rand(3, 5);
        for ($c = 1; $c <= $numChapters; $c++) {
            $chapId = DB::table('chapters')->insertGetId([
                'course_id' => $courseId,
                'title' => "Chương $c: Kiến thức nền tảng phần $c",
                'sort_order' => $c,
                'created_at' => now(), 'updated_at' => now()
            ]);

            // Tạo 4-6 Bài học cho mỗi chương (Xen kẽ Video & Quiz)
            $numLessons = rand(4, 6);
            for ($l = 1; $l <= $numLessons; $l++) {
                $type = ($l % 3 == 0) ? 'quiz' : 'video'; // Cứ 3 bài thì 1 quiz
                
                $content = null;
                if ($type == 'quiz') {
                    // Tạo nội dung Quiz "xịn" nhiều câu hỏi
                    $quizData = [];
                    for ($q = 1; $q <= 5; $q++) { // 5 câu hỏi mỗi bài quiz
                        $quizData[] = [
                            'question' => "Câu hỏi trắc nghiệm số $q của chương $c?",
                            'options' => ['Đáp án A', 'Đáp án B', 'Đáp án C', 'Đáp án D'],
                            'correct' => rand(0, 3)
                        ];
                    }
                    $content = json_encode($quizData);
                }

                DB::table('lessons')->insert([
                    'chapter_id' => $chapId,
                    'title' => "Bài $l: " . ($type == 'video' ? 'Video bài giảng lý thuyết' : 'Bài kiểm tra kiến thức'),
                    'type' => $type,
                    'duration' => rand(10, 60), // 10-60 phút
                    'content' => $content,
                    'sort_order' => $l,
                    'created_at' => now(), 'updated_at' => now()
                ]);
            }
        }
    }

    // =========================================================================
    // PHẦN 3: MÔ PHỎNG HỌC TẬP (CÓ HISTORY)
    // =========================================================================

    private function simulateLearningProcess($userId, $courseId, $type)
    {
        if ($type == 'dropout') return; // Bỏ học thì không làm gì cả

        // 1. Ghi danh
        DB::table('enrollments')->insertOrIgnore([
            'user_id' => $userId, 'course_id' => $courseId,
            'status' => 'active', 'created_at' => now()->subMonths(3), 'updated_at' => now()
        ]);

        // Cấu hình hành vi
        $config = match($type) {
            'excellent' => ['min_score' => 8.5, 'completion' => 100, 'attempts' => [1, 1]], // Giỏi: Làm hết, ít phải làm lại
            'at_risk'   => ['min_score' => 2.0, 'completion' => 20,  'attempts' => [1, 4]], // Yếu: Làm ít, điểm thấp, phải làm lại nhiều
            default     => ['min_score' => 5.0, 'completion' => 70,  'attempts' => [1, 2]]  // Trung bình
        };

        // Lấy danh sách bài học
        $lessons = DB::table('lessons')
            ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->select('lessons.*')
            ->get();

        foreach ($lessons as $lesson) {
            // Quyết định có học bài này không (dựa trên tỷ lệ completion)
            if (rand(0, 100) > $config['completion']) continue;

            $this->processLessonSubmission($userId, $lesson, $config);
        }
    }

    private function processLessonSubmission($userId, $lesson, $config)
    {
        // Random số lần làm lại bài (Attempt)
        $numAttempts = rand($config['attempts'][0], $config['attempts'][1]);
        
        $finalScore = 0;
        $finalStatus = 'pending';
        $finalProgress = 0;

        // Giả lập từng lần làm bài (Lưu History)
        for ($i = 1; $i <= $numAttempts; $i++) {
            // Lần đầu điểm thường thấp hơn, các lần sau cải thiện
            $baseScore = rand($config['min_score'] * 10, 100) / 10;
            $currentScore = ($lesson->type == 'quiz') ? min(10, $baseScore + ($i * 0.5)) : null; // Điểm tăng dần
            
            $progress = ($lesson->type == 'video') ? rand(100, 900) : 0;
            
            // Logic status
            $isCompleted = ($lesson->type == 'quiz') || ($progress > 800);
            $status = $isCompleted ? 'completed' : 'pending';

            // Lưu các biến cuối cùng để update bảng chính
            $finalScore = $currentScore;
            $finalStatus = $status;
            $finalProgress = $progress;

            // --- QUAN TRỌNG: KIỂM TRA/TẠO MAIN SUBMISSION TRƯỚC ---
            // Phải có dòng trong lesson_submissions thì mới có ID để gán cho history
            $subData = [
                'score' => $currentScore,
                'status' => $status,
                'attempt_count' => $i,
                'video_progress' => $progress,
                'updated_at' => now()->subDays(rand(1, 30))
            ];

            $existingSub = DB::table('lesson_submissions')
                ->where('user_id', $userId)->where('lesson_id', $lesson->id)->first();

            if ($existingSub) {
                DB::table('lesson_submissions')->where('id', $existingSub->id)->update($subData);
                $submissionId = $existingSub->id;
            } else {
                $submissionId = DB::table('lesson_submissions')->insertGetId(array_merge($subData, [
                    'user_id' => $userId, 
                    'lesson_id' => $lesson->id, 
                    'created_at' => now()->subMonths(2)
                ]));
            }

            // --- LƯU HISTORY ---
            DB::table('lesson_submission_histories')->insert([
                'lesson_submission_id' => $submissionId,
                'attempt_number' => $i,
                'score' => $currentScore,
                'submission_content' => json_encode(['note' => "Lần thử thứ $i"]),
                'submitted_at' => now()->subDays(rand(1, 30)),
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }

    private function generateLoginLogs($userId, $type)
    {
        $count = match($type) {
            'excellent' => rand(50, 100),
            'at_risk'   => rand(0, 5),
            'dropout'   => 0,
            default     => rand(15, 40)
        };

        $logs = [];
        for ($i = 0; $i < $count; $i++) {
            $logs[] = [
                'user_id' => $userId, 
                'action' => 'login', 
                'meta_data' => json_encode(['ip' => '127.0.0.1']), 
                'created_at' => Carbon::now()->subDays(rand(1, 90)), 
                'updated_at' => now()
            ];
        }
        if (!empty($logs)) DB::table('learning_logs')->insert($logs);
    }
    
    private function createFakeStudents($count, $allClassIds) {
        $data = []; 
        $pass = Hash::make('12345678');
        for($i=1; $i<=$count; $i++) {
            $data[] = [
                'name' => "Sinh viên Mẫu $i", 
                'email' => "sv_fake_$i@edu.vn", 
                'password' => $pass, 
                'role' => 'student', 
                'classroom_id' => $allClassIds[array_rand($allClassIds)], 
                'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('users')->insert($data);
        return User::where('email', 'like', 'sv_fake_%')->get();
    }
}