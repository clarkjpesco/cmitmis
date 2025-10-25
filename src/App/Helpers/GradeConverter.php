<?php

declare(strict_types=1);

namespace App\Helpers;

class GradeConverter
{
    /**
     * Convert percentage to school grade (1.0 - 9.0)
     */
    public static function percentageToGrade(float $percentage): float
    {
        if ($percentage >= 99) return 1.0;
        if ($percentage >= 97) return 1.1;
        if ($percentage >= 95) return 1.2;
        if ($percentage >= 93) return 1.3;
        if ($percentage >= 91) return 1.4;
        if ($percentage >= 90) return 1.5;
        if ($percentage >= 89) return 1.6;
        if ($percentage >= 88) return 1.7;
        if ($percentage >= 87) return 1.8;
        if ($percentage >= 86) return 1.9;
        if ($percentage >= 85) return 2.0;
        if ($percentage >= 84) return 2.1;
        if ($percentage >= 83) return 2.2;
        if ($percentage >= 82) return 2.3;
        if ($percentage >= 81) return 2.4;
        if ($percentage >= 80) return 2.5;
        if ($percentage >= 79) return 2.6;
        if ($percentage >= 78) return 2.7;
        if ($percentage >= 77) return 2.8;
        if ($percentage >= 76) return 2.9;
        if ($percentage >= 75) return 3.0;
        return 5.0; // Failed
    }

    /**
     * Get grade description
     */
    public static function getGradeDescription(float $grade): string
    {
        if ($grade >= 1.0 && $grade <= 1.2) return 'Excellent';
        if ($grade >= 1.3 && $grade <= 1.5) return 'Very Good';
        if ($grade >= 1.6 && $grade <= 2.0) return 'Good';
        if ($grade >= 2.1 && $grade <= 2.5) return 'Satisfactory';
        if ($grade >= 2.6 && $grade <= 3.0) return 'Fair';
        if ($grade === 4.0) return 'Incomplete';
        if ($grade === 5.0) return 'Failed';
        if ($grade === 7.0) return 'Withdrawn';
        if ($grade === 9.0) return 'Dropped';
        return 'Invalid Grade';
    }

    /**
     * Check if grade is passing
     */
    public static function isPassing(float $grade): bool
    {
        return $grade >= 1.0 && $grade <= 3.0;
    }

    /**
     * Check if grade counts toward GWA
     */
    public static function countsTowardGWA(float $grade): bool
    {
        // Only passing grades (1.0-3.0) count toward GWA
        // INC (4.0), Failed (5.0), Withdrawn (7.0), Dropped (9.0) don't count
        return $grade >= 1.0 && $grade <= 3.0;
    }

    /**
     * Get CSS class for grade display
     */
    public static function getGradeClass(float $grade): string
    {
        if ($grade >= 1.0 && $grade <= 1.5) return 'bg-green-100 text-green-800';
        if ($grade >= 1.6 && $grade <= 2.0) return 'bg-blue-100 text-blue-800';
        if ($grade >= 2.1 && $grade <= 2.5) return 'bg-cyan-100 text-cyan-800';
        if ($grade >= 2.6 && $grade <= 3.0) return 'bg-yellow-100 text-yellow-800';
        if ($grade === 4.0) return 'bg-purple-100 text-purple-800';
        if ($grade === 5.0) return 'bg-red-100 text-red-800';
        if ($grade === 7.0) return 'bg-orange-100 text-orange-800';
        if ($grade === 9.0) return 'bg-gray-100 text-gray-800';
        return 'bg-gray-100 text-gray-800';
    }

    /**
     * Get percentage range for a grade
     */
    public static function getPercentageRange(float $grade): string
    {
        $ranges = [
            1.0 => '99-100%',
            1.1 => '97-98%',
            1.2 => '95-96%',
            1.3 => '93-94%',
            1.4 => '91-92%',
            1.5 => '90%',
            1.6 => '89%',
            1.7 => '88%',
            1.8 => '87%',
            1.9 => '86%',
            2.0 => '85%',
            2.1 => '84%',
            2.2 => '83%',
            2.3 => '82%',
            2.4 => '81%',
            2.5 => '80%',
            2.6 => '79%',
            2.7 => '78%',
            2.8 => '77%',
            2.9 => '76%',
            3.0 => '75%',
            4.0 => 'Incomplete',
            5.0 => 'Below 75%',
            7.0 => 'Withdrawn',
            9.0 => 'Dropped'
        ];

        return $ranges[$grade] ?? 'Unknown';
    }

    /**
     * Calculate GWA (General Weighted Average)
     * Only includes passing grades (1.0-3.0)
     */
    public static function calculateGWA(array $grades, array $units): float
    {
        $totalWeightedGrades = 0;
        $totalUnits = 0;

        foreach ($grades as $index => $grade) {
            if ($grade !== null && self::countsTowardGWA($grade)) {
                $totalWeightedGrades += $grade * $units[$index];
                $totalUnits += $units[$index];
            }
        }

        if ($totalUnits === 0) return 0;

        return round($totalWeightedGrades / $totalUnits, 2);
    }

    /**
     * Get honor/award based on GWA
     */
    public static function getHonorAward(float $gwa): ?string
    {
        if ($gwa >= 1.0 && $gwa <= 1.2) return 'Summa Cum Laude';
        if ($gwa >= 1.21 && $gwa <= 1.45) return 'Magna Cum Laude';
        if ($gwa >= 1.46 && $gwa <= 1.75) return 'Cum Laude';
        if ($gwa >= 1.76 && $gwa <= 2.0) return "Dean's List";
        return null;
    }

    /**
     * Validate if grade is valid
     */
    public static function isValidGrade(float $grade): bool
    {
        $validGrades = [
            1.0,
            1.1,
            1.2,
            1.3,
            1.4,
            1.5,
            1.6,
            1.7,
            1.8,
            1.9,
            2.0,
            2.1,
            2.2,
            2.3,
            2.4,
            2.5,
            2.6,
            2.7,
            2.8,
            2.9,
            3.0,
            4.0,
            5.0,
            7.0,
            9.0
        ];
        return in_array($grade, $validGrades);
    }

    /**
     * Get all valid grades
     */
    public static function getAllGrades(): array
    {
        return [
            ['grade' => 1.0, 'percentage' => '99-100%', 'description' => 'Excellent'],
            ['grade' => 1.1, 'percentage' => '97-98%', 'description' => 'Excellent'],
            ['grade' => 1.2, 'percentage' => '95-96%', 'description' => 'Excellent'],
            ['grade' => 1.3, 'percentage' => '93-94%', 'description' => 'Very Good'],
            ['grade' => 1.4, 'percentage' => '91-92%', 'description' => 'Very Good'],
            ['grade' => 1.5, 'percentage' => '90%', 'description' => 'Very Good'],
            ['grade' => 1.6, 'percentage' => '89%', 'description' => 'Good'],
            ['grade' => 1.7, 'percentage' => '88%', 'description' => 'Good'],
            ['grade' => 1.8, 'percentage' => '87%', 'description' => 'Good'],
            ['grade' => 1.9, 'percentage' => '86%', 'description' => 'Good'],
            ['grade' => 2.0, 'percentage' => '85%', 'description' => 'Good'],
            ['grade' => 2.1, 'percentage' => '84%', 'description' => 'Satisfactory'],
            ['grade' => 2.2, 'percentage' => '83%', 'description' => 'Satisfactory'],
            ['grade' => 2.3, 'percentage' => '82%', 'description' => 'Satisfactory'],
            ['grade' => 2.4, 'percentage' => '81%', 'description' => 'Satisfactory'],
            ['grade' => 2.5, 'percentage' => '80%', 'description' => 'Satisfactory'],
            ['grade' => 2.6, 'percentage' => '79%', 'description' => 'Fair'],
            ['grade' => 2.7, 'percentage' => '78%', 'description' => 'Fair'],
            ['grade' => 2.8, 'percentage' => '77%', 'description' => 'Fair'],
            ['grade' => 2.9, 'percentage' => '76%', 'description' => 'Fair'],
            ['grade' => 3.0, 'percentage' => '75%', 'description' => 'Fair'],
            ['grade' => 4.0, 'percentage' => 'INC', 'description' => 'Incomplete'],
            ['grade' => 5.0, 'percentage' => 'Below 75%', 'description' => 'Failed'],
            ['grade' => 7.0, 'percentage' => 'WD', 'description' => 'Withdrawn'],
            ['grade' => 9.0, 'percentage' => 'DRP', 'description' => 'Dropped'],
        ];
    }

    /**
     * Get grade status
     */
    public static function getGradeStatus(float $grade): string
    {
        if ($grade >= 1.0 && $grade <= 3.0) return 'passed';
        if ($grade === 4.0) return 'incomplete';
        if ($grade === 5.0) return 'failed';
        if ($grade === 7.0) return 'withdrawn';
        if ($grade === 9.0) return 'dropped';
        return 'unknown';
    }
}
