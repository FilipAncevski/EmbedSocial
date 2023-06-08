<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        return Inertia::render('Review/Index');
    }

    public function show(Request $request)
    {
        $jsonContents = Storage::disk('local')->get('reviews.json');
        $reviewsData = json_decode($jsonContents, true);

        $prioritizeText = $request->get('prioritizeText');
        $sortRating = $request->get('sortRating');
        $sortDate = $request->get('sortDate');
        $filterValue = $request->get('minRating');

        $reviews = $this->sortedByDate($reviewsData, $sortDate);
        $reviews = $this->sortedByRating($reviews, $sortRating);
        $reviews = $this->sortedByText($reviews, $prioritizeText);
        $reviews = $this->filterByMinRating($reviews, $filterValue);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    private function sortedByText(array $reviews, ?string $text): array
    {
        if ($text == 'no'){
            return $reviews;
        }

        $reviewsWithText = array_filter($reviews, function ($review) {
            return !empty($review['reviewFullText']);
        });

        $reviewsWithoutText = array_filter($reviews, function ($review) {
            return empty($review['reviewFullText']);
        });

        return array_merge($reviewsWithText, $reviewsWithoutText);
    }


    private function sortedByRating(array $reviews, ?string $rating): array
    {
        if ($rating && in_array($rating, ['highest', 'lowest'])) {
            usort($reviews, function ($a, $b) use ($rating) {
                if ($rating === 'highest') {
                    return $b['rating'] <=> $a['rating'];
                } else {
                    return $a['rating'] <=> $b['rating'];
                }
            });
        }

        return $reviews;
    }



    private function sortedByDate(array $reviews, ?string $date): array
    {
        if ($date === 'newest') {
            usort($reviews, function($a, $b) {
                return strtotime($b['reviewCreatedOnDate']) - strtotime($a['reviewCreatedOnDate']);
            });
        } elseif ($date === 'oldest') {
            usort($reviews, function($a, $b) {
                return strtotime($a['reviewCreatedOnDate']) - strtotime($b['reviewCreatedOnDate']);
            });
        }

        return $reviews;
    }

    private function filterByMinRating(array $reviews,?string $minRate): array
    {
        $filteredReviews = [];

        foreach ($reviews as $review) {
            if ($review['rating'] >= $minRate) {
                $filteredReviews[] = $review;
            }
        }

        return $filteredReviews;
    }
}
