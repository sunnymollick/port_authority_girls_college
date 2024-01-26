<?php

namespace App\Helper;

use DB;

class GenerateMarksheet
{

   public static function generateSummeryResult($exam_id, $class_id, $section_id, $year)
   {
      DB::statement(DB::raw("set @rownum=0, @class_id='$class_id',@test_marks=0, @resultStatus='PASSED',@ctPmarks=0,@mPmarks=0, @section_id='$section_id', @exam_id='$exam_id', @year='$year'"));

      $createSummeryTempTables = DB::unprepared(
        DB::raw("
            CREATE TEMPORARY TABLE summery_result( exmId INT, examTitle VARCHAR(100), stdCode VARCHAR(50),stdSession VARCHAR(50), stdName VARCHAR(100), stdClass VARCHAR(50),
            stdRoll VARCHAR(50), stdSection INT, subId INT, optionalsubId INT, ctPMarks INT, mainPMarks INT, obtainedMark INT,
            fiftyToHundredMark INT,status VARCHAR(50), grade VARCHAR(5), gpa DECIMAL(10,2))"
        )
      );

      if ($createSummeryTempTables) {
         DB::insert("insert into summery_result select exams.id,exams.name, std.std_code,std.std_session, std.name,marks.class_id,enrolls.roll,
          marks.section_id,marks.subject_id, enrolls.subject_id,
             @ctPmarks := CEIL(marks.ct_marks*exams.ct_marks_percentage/if(sub.ct_marks=0,1,sub.ct_marks)) AS ctPercentMarks,
            @mPmarks := CEIL( marks.total_marks*(sub.subject_marks - exams.ct_marks_percentage)/(sub.theory_marks+sub.mcq_marks + 
                 sub.practical_marks)) AS mainPercentMarks,	
           @mPmarks + @ctPmarks AS obtained_nmarks,
           
           IF(sub.subject_marks = 50 ,@test_marks := CEIL((@ctPmarks+@mPmarks)*100/sub.subject_marks), @test_marks := (@ctPmarks+@mPmarks)) AS fiftyToHundredMark,

                     @resultStatus :=   case when marks.subject_id != enrolls.subject_id then (
                        case when  marks.theory_marks>=sub.theory_pass_marks 
                        then (case when marks.mcq_marks>=sub.mcq_pass_marks 
								then (case when marks.practical_marks>=sub.practical_pass_marks 							                        
                        then (case when 
								CEIL( marks.total_marks*(sub.subject_marks-exams.ct_marks_percentage)/(sub.theory_marks+sub.mcq_marks + 
				sub.practical_marks) + marks.ct_marks*exams.ct_marks_percentage/if(sub.ct_marks=0,1,sub.ct_marks)) >=sub.pass_marks 
								then 'PASSED' else 'FAILED' end) else 'FAILED' END) else 'FAILED' END) 
                        else 'FAILED' END) else 'PASSED' end as result,
				CASE 
		         WHEN  @resultStatus = 'PASSED' THEN (
               CASE
               WHEN  @test_marks >= 80 THEN 'A+' 
               WHEN  @test_marks >= 70 and @test_marks <= 79 THEN 'A'
               WHEN  @test_marks >= 60 and @test_marks <= 69 THEN 'A-' 
               WHEN  @test_marks >= 50 and @test_marks <= 59 THEN 'B' 
               WHEN  @test_marks >= 40 and @test_marks <= 49 THEN 'C' 
               WHEN  @test_marks >= 33 and @test_marks <= 39 THEN 'D'                
               ELSE   'F' END ) ELSE 'F' END AS grade,
				CASE 
               WHEN @resultStatus = 'PASSED' THEN (
               CASE 
               WHEN  @test_marks >= 80 THEN '5.00' 
               WHEN  @test_marks >= 70 and @test_marks <= 79 THEN '4.00'
               WHEN  @test_marks >= 60 and @test_marks <= 69 THEN '3.50' 
               WHEN  @test_marks >= 50 and @test_marks <= 59 THEN '3.00' 
               WHEN  @test_marks >= 40 and @test_marks <= 49 THEN '2.00' 
               WHEN  @test_marks >= 33 and @test_marks <= 39 THEN '1.00' 
               ELSE   '0.00' END ) ELSE '0.00' END AS gpa                         							                        
								
								from  marks                     
                        inner JOIN students as std on std.std_code = marks.student_code
                        LEFT JOIN enrolls  on enrolls.student_id = std.id AND enrolls.class_id = @class_id  and enrolls.YEAR= @year
                        LEFT JOIN exams on exams.id = marks.exam_id
                        LEFT JOIN subjects as sub on sub.id = marks.subject_id
                        where marks.class_id =@class_id and marks.exam_id=@exam_id and marks.section_id=@section_id AND marks.YEAR =@year
                        order by marks.student_code,sub.subject_order ASC");

         $result = DB::select("SELECT rownum,exmId,examTitle,stdCode,stdSession,stdName,stdClass,stdRoll,stdSection,subId,totalSubject,hasOptional,mainSubPoint,optionalSubPoint,totalMarks,failedSubject,CASE  WHEN T2.CNT = 0  THEN 'PASSED'  ELSE 'FAILED'  END result
               FROM (
               SELECT @rownum  := @rownum  + 1 AS rownum, exmId,examTitle,stdCode,stdSession,stdName,stdClass,stdRoll,stdSection,subId, 
               sum(case when subId != optionalsubId then 1 END) AS totalSubject, 
               @cgpaPoint := sum(case when subId != optionalsubId then gpa END) AS mainSubPoint,
               count(case when subId = optionalsubId then 1 END) AS hasOptional,
               @optionalSubPoint := sum(case when subId = optionalsubId then (case when gpa>2 then gpa-2 END) ELSE 0 END) AS optionalSubPoint,
               sum(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) AS failedSubject,
               SUM(obtainedMark) AS totalMarks, COUNT(CASE WHEN status = 'FAILED' THEN 1 END) CNT
                    FROM summery_result GROUP BY stdCode) T2");

         DB::unprepared(DB::raw(" DROP TABLE IF EXISTS summery_result"));

         return $result;
      }
   }

   public static function generateMarksheetResult($exam_id, $class_id, $section_id, $student_code, $year)
   {
      DB::statement(DB::raw("set @class_id='$class_id', @section_id='$section_id', @exam_id='$exam_id', @std_code='$student_code', @year='$year',
      @test_marks=0, @resultStatus='PASSED',@ctPmarks=0,@mPmarks=0"));

      $createMarksheetTempTables = DB::unprepared(
        DB::raw("
            CREATE TEMPORARY TABLE temp_marksheet_result(stdCode VARCHAR(50),stdRoll VARCHAR(50), subId INT, optionalsubId INT, ctPMarks INT, mainPMarks INT, obtainedMark INT, result VARCHAR(50))"
        )
      );

      if ($createMarksheetTempTables) {
         DB::insert("insert into temp_marksheet_result select marks.student_code, enrolls.roll ,marks.subject_id, enrolls.subject_id,	
   @ctPmarks := CEIL(marks.ct_marks*exams.ct_marks_percentage/if(sub.ct_marks=0,1,sub.ct_marks)) AS ctPercentMarks,
   @mPmarks := CEIL( marks.total_marks*(sub.subject_marks - exams.ct_marks_percentage)/(sub.theory_marks+sub.mcq_marks + 
				sub.practical_marks)) AS mainPercentMarks,	
	@mPmarks + @ctPmarks AS obtained_nmarks,
	case when marks.subject_id != -1 then (
                        case when  marks.theory_marks>=sub.theory_pass_marks 
                        then (case when marks.mcq_marks>=sub.mcq_pass_marks 
								then (case when marks.practical_marks>=sub.practical_pass_marks 							                        
                        then (case when  	@mPmarks + @ctPmarks >=sub.pass_marks 
								then 'P' else 'F' end) else 'F' END) else 'F' END) 
                        else 'F' END) else 'P' end as result
 FROM marks 
 LEFT JOIN exams on exams.id = marks.exam_id
 LEFT JOIN subjects as sub on sub.id = marks.subject_id
 LEFT JOIN students as std on std.std_code = marks.student_code
 LEFT JOIN enrolls  on enrolls.student_id = std.id AND enrolls.class_id = @class_id  and enrolls.year= @year                        
 WHERE marks.exam_id =@exam_id and marks.class_id = @class_id AND marks.section_id =@section_id and marks.student_code = @std_code and marks.year = @year");

         $result = DB::select("SELECT  marks.id, marks.exam_id,std.name,std.std_code,temp.stdRoll,exams.name AS exam_name,sub.name as subject,sub.subject_marks,marks.class_id,marks.subject_id,temp.optionalsubId AS optional_subject, marks.theory_marks, marks.mcq_marks, marks.practical_marks, marks.ct_marks,marks.total_marks,
 temp.ctPMarks,temp.mainPMarks, temp.obtainedMark,max_score.hmarks AS highest_marks, temp.result as result_status, 	
 
     IF(sub.subject_marks = 50 ,@test_marks := CEIL((temp.ctPMarks+temp.mainPMarks)*100/sub.subject_marks), @test_marks := (temp.ctPMarks+temp.mainPMarks)) AS fiftyToHundredMark,	
				 CASE 
		         WHEN  temp.result = 'P' THEN (
               CASE
               WHEN  @test_marks >= 80 THEN 'A+' 
               WHEN  @test_marks >= 70 and @test_marks <= 79 THEN 'A'
               WHEN  @test_marks >= 60 and @test_marks <= 69 THEN 'A-' 
               WHEN  @test_marks >= 50 and @test_marks <= 59 THEN 'B' 
               WHEN  @test_marks >= 40 and @test_marks <= 49 THEN 'C' 
               WHEN  @test_marks >= 33 and @test_marks <= 39 THEN 'D'                
               ELSE   'F' END ) ELSE 'F' END AS grade,
				CASE 
               WHEN temp.result = 'P' THEN (
               CASE 
               WHEN  @test_marks >= 80 THEN '5.00' 
               WHEN  @test_marks >= 70 and @test_marks <= 79 THEN '4.00'
               WHEN  @test_marks >= 60 and @test_marks <= 69 THEN '3.50' 
               WHEN  @test_marks >= 50 and @test_marks <= 59 THEN '3.00' 
               WHEN  @test_marks >= 40 and @test_marks <= 49 THEN '2.00' 
               WHEN  @test_marks >= 33 and @test_marks <= 39 THEN '1.00' 
               ELSE   '0.00' END ) ELSE '0.00' END AS CGPA       
               FROM marks
               LEFT JOIN 
               (
               SELECT marks.subject_id,  
					MAX(CEIL( marks.total_marks*(sub.subject_marks - exams.ct_marks_percentage)/(sub.theory_marks+sub.mcq_marks + 
				   sub.practical_marks)) + CEIL(marks.ct_marks*exams.ct_marks_percentage/if(sub.ct_marks=0,1,sub.ct_marks))) 
					as hmarks FROM marks
					JOIN exams  on exams.id = marks.exam_id
					JOIN subjects AS sub on sub.id = marks.subject_id
               WHERE marks.exam_id =@exam_id and marks.class_id = @class_id
               GROUP BY marks.exam_id,marks.class_id,marks.subject_id 
               ) 
               as max_score
               on marks.subject_id = max_score.subject_id
               
               LEFT JOIN students as std on std.std_code = marks.student_code
               LEFT JOIN exams  on exams.id = marks.exam_id
               LEFT JOIN subjects as sub on sub.id = marks.subject_id
               LEFT JOIN temp_marksheet_result as temp on temp.subId = marks.subject_id
               WHERE marks.exam_id =@exam_id and marks.class_id = @class_id and marks.section_id =@section_id and 
					marks.student_code = @std_code and marks.year = @year
               order by marks.student_code,sub.subject_order ASC");

         DB::unprepared(DB::raw(" DROP TABLE IF EXISTS temp_marksheet_result"));

         return $result;
      }
   }
}