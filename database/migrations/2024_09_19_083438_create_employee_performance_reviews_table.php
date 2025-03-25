<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employee')->onDelete('cascade'); // Liên kết đến bảng nhân viên
            $table->string('month');  // Tháng đánh giá
            $table->integer('year');  // Năm đánh giá

            // Các tiêu chí đánh giá
            $table->integer('attendance_score')->default(0);  // Điểm về thời gian làm việc
            $table->integer('quality_score')->default(0);  // Điểm về chất lượng công việc
            $table->integer('productivity_score')->default(0);  // Điểm về năng suất làm việc
            $table->integer('teamwork_score')->default(0);  // Điểm về làm việc nhóm
            $table->integer('creativity_score')->default(0);  // Điểm về sáng tạo và cải tiến
            $table->integer('initiative_score')->default(0);  // Điểm về tính tự giác và trách nhiệm
            $table->integer('development_score')->default(0);  // Điểm về khả năng phát triển bản thân
            $table->integer('problem_solving_score')->default(0);  // Điểm về giải quyết vấn đề

            $table->integer('overall_score')->default(0);  // Điểm tổng quát

            // Thông tin về kết quả đánh giá và khen thưởng
            $table->string('evaluation_result')->nullable();  // Kết quả đánh giá (Xuất sắc, Tốt, Trung bình, Kém...)
            $table->string('reward')->nullable();  // Phần thưởng nếu có

            $table->timestamps();  // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_performance_reviews');
    }
};
