<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use DB;
//for password encryption or hash protected
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function addReview(Request $request)
    {
        $post_params = $request->all();
        $review_id = DB::table('reviews')->insertGetId([
            'review_title' => $post_params['review_title'],
            'review_content' => $post_params['review_content'],
            'customer_id' => $post_params['customer_id'],
            'reviewer_id' => $post_params['reviewer_id'],
            'review_grade' => $post_params['review_grade'],
            'date_add' => date('Y-m-d h:i:s'),
        ]);

        if ($review_id > 0) {
            $grades = $post_params['grades'];
            $cnt = 0;
            foreach ($grades as $grade) {
                $grade_id = DB::table('review_grade')->insert([
                    'review_id' => $review_id,
                    'cate_id' => $grade['cate_id'],
                    'grade' => $grade['grade'],
                ]);
                if ($grade_id > 0) {
                    $cnt++;
                }

            }

            $responseData = [
                'success' => 1,
                'data' => $post_params,
                'message' => "Review saved successfully, {$cnt} grades saved",
            ];

        } else {
            $responseData = [
                'success' => 0,
                'data' => array(),
                'message' => 'Error while creating review entry',
            ];
        }
        print json_encode($responseData);
    }

    public function getReview($review_id)
    {
        $review = DB::table('reviews')->where('review_id', $review_id)->first();
        if ($review) {
            $review_grades = DB::table('review_grade')
                ->leftJoin('review_category', "review_grade.cate_id", "=", "review_category.id")
                ->where('review_grade.review_id', $review['review_id'])->get();
            $responseData = [
                'success' => 1,
                'data' => [
                    "review" => $review,
                    "grade"  => $review_grades
                ],
                'message' => 'Data get successfully.',
            ];
        } else {
            $responseData = [
                'success' => 0,
                'data' => array(),
                'message' => 'No review data',
            ];
        }
        print json_encode($responseData);

    }

    public function getReviewList($user_id)
    {
        $review = DB::table('reviews')->where('review_id', $review_id)->first();
        if ($result) {
            $responseData = array('success' => '1', 'data' => $result, 'message' => 'Returned review');
        } else {
            $responseData = array('success' => '0', 'data' => array(), 'message' => 'No data');
        }
        print json_encode($responseData);
    }

    public function getReviewCategories()
    {
        $categories = DB::table('review_category')
                     ->joingLeft('review_cate_desc', 'review_category.id', '=', 'review_cate_desc.cate_id')
                     ->get();
        if ($categories) {
            $responseData = array('success' => '1', 'data' => $categories, 'message' => 'Returned review');
        } else {
            $responseData = array('success' => '0', 'data' => array(), 'message' => 'No data');
        }
        print json_encode($responseData);
    }

    public function deleteReview($review_id)
    {
        DB::table('reviews')->where('review_id', $review_id)->delete();
        DB::table('review_grade')->where('review_id', $review_id)->delete(); 
        $responseData = array('success' => '1', 'data' => array(), 'message' => 'review deleted successfully');
        print json_encode($responseData);
    }
}
