<?php

namespace App\Imports;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class CourseContentImport implements ToCollection, WithHeadingRow
{
    protected $courseId;
    protected $currentChapterId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
        $this->currentChapterId = null;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row['ten_chuong'])) {
                $chapterTitle = trim($row['ten_chuong']);
                
                $nextOrder = Chapter::where('course_id', $this->courseId)->max('sort_order') + 1;

                $chapter = Chapter::create([
                    'course_id' => $this->courseId,
                    'title' => $chapterTitle,
                    'sort_order' => $nextOrder
                ]);
                
                $this->currentChapterId = $chapter->id;
            }

            if (!empty($row['ten_bai_hoc']) && $this->currentChapterId) {
                $lessonTitle = trim($row['ten_bai_hoc']);
                $type = $this->normalizeType($row['loai_noi_dung'] ?? 'document');
                
                $content = $row['noi_dung'] ?? '';
                if ($type === 'quiz') {
                    $content = $this->parseQuizContent($content);
                }

                Lesson::create([
                    'chapter_id' => $this->currentChapterId,
                    'title' => $lessonTitle,
                    'slug' => Str::slug($lessonTitle) . '-' . time() . rand(10,99),
                    'type' => $type,
                    'video_url' => !empty($row['video_url']) ? $row['video_url'] : null,
                    'duration' => is_numeric($row['thoi_luong']) ? $row['thoi_luong'] : 0,
                    'content' => $content,
                    'sort_order' => Lesson::where('chapter_id', $this->currentChapterId)->max('sort_order') + 1,
                    'file_path' => null
                ]);
            }
        }
    }

    private function normalizeType($input)
    {
        $input = strtolower(trim($input));
        if (in_array($input, ['video', 'phim'])) return 'video';
        if (in_array($input, ['quiz', 'trắc nghiệm', 'câu hỏi'])) return 'quiz';
        if (in_array($input, ['homework', 'bài tập', 'tự luận'])) return 'homework';
        return 'document';
    }

    private function parseQuizContent($rawText)
    {
        if (empty($rawText)) return json_encode([]);

        $questions = [];
        $lines = explode("\n", $rawText);

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line));
            
            if (count($parts) < 3) continue;

            $questionText = array_shift($parts);
            $options = [];
            $correctIndex = 0;

            foreach ($parts as $index => $opt) {
                if (str_starts_with($opt, '*')) {
                    $correctIndex = $index;
                    $opt = substr($opt, 1);
                }
                $options[] = $opt;
            }

            if (count($options) >= 2) {
                $questions[] = [
                    'question' => $questionText,
                    'options' => $options,
                    'correct' => $correctIndex
                ];
            }
        }

        return json_encode($questions);
    }
}