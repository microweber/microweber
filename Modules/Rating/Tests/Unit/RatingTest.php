<?php

namespace Modules\Rating\Tests\Unit;

use Modules\Content\Models\Content;
use Modules\Rating\Models\Rating;
use MicroweberPackages\Core\tests\TestCase ;

class RatingTest extends TestCase
{
    public function testAddRatingToContent()
    {

        //cleanup
        Rating::where('rel_type', morph_name(\Modules\Content\Models\Content::class))->delete();

        // Create a test content
        $content = new Content();
        $content->title = 'Test Article';
        $content->save();

        // Create a rating for the content
        $rating = new Rating();
        $rating->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating->rel_id = $content->id;
        $rating->rating = 5;
        $rating->comment = 'Great article!';
        $rating->session_id = session()->getId();
        $rating->save();

        // Test if rating was saved
        $this->assertNotNull($rating->id);
        $this->assertEquals(5, $rating->rating);
        $this->assertEquals('Great article!', $rating->comment);

        // Test retrieving rating
        $savedRating = Rating::where('rel_id', $content->id)
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->first();

        $this->assertNotNull($savedRating);
        $this->assertEquals($rating->id, $savedRating->id);
    }

    public function testUpdateRating()
    {
        //cleanup



        // Create initial rating
        $rating = new Rating();
        $rating->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating->rel_id = '1';
        $rating->rating = 3;
        $rating->comment = 'Initial comment';
        $rating->session_id = session()->getId();
        $rating->save();

        // Update rating
        $rating->rating = 4;
        $rating->comment = 'Updated comment';
        $rating->save();

        // Verify update
        $updatedRating = Rating::find($rating->id);
        $this->assertEquals(4, $updatedRating->rating);
        $this->assertEquals('Updated comment', $updatedRating->comment);
    }

    public function testDeleteRating()
    {
        // Create a rating
        $rating = new Rating();
        $rating->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating->rel_id = '1';
        $rating->rating = 5;
        $rating->session_id = session()->getId();
        $rating->save();

        $ratingId = $rating->id;

        // Delete the rating
        $rating->delete();

        // Verify deletion
        $deletedRating = Rating::find($ratingId);
        $this->assertNull($deletedRating);
    }

    public function testAverageRating()
    {
        // Create a test content
        $content = new Content();
        $content->title = 'Test Product';
        $content->save();

        // Add multiple ratings
        $ratings = [
            ['rating' => 5, 'session_id' => 'session1'],
            ['rating' => 4, 'session_id' => 'session2'],
            ['rating' => 3, 'session_id' => 'session3']
        ];

        foreach ($ratings as $ratingData) {
            $rating = new Rating();
            $rating->rel_type = morph_name(\Modules\Content\Models\Content::class);
            $rating->rel_id = $content->id;
            $rating->rating = $ratingData['rating'];
            $rating->session_id = $ratingData['session_id'];
            $rating->save();
        }

        // Calculate average
        $average = Rating::where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->where('rel_id', $content->id)
            ->avg('rating');

        $this->assertEquals(4, $average);
    }


    public function testRatingCaching()
    {
        $content = new Content();
        $content->title = 'Cache Test Article';
        $content->save();

        // Create ratings
        $rating1 = new Rating();
        $rating1->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating1->rel_id = $content->id;
        $rating1->rating = 5;
        $rating1->session_id = 'session1';
        $rating1->save();

        $rating2 = new Rating();
        $rating2->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating2->rel_id = $content->id;
        $rating2->rating = 3;
        $rating2->session_id = 'session2';
        $rating2->save();

        // Test cached average
        $cachedAverage = Rating::getAverage(morph_name(\Modules\Content\Models\Content::class), $content->id);
        $this->assertEquals(4, $cachedAverage);

        // Add new rating and verify cache is updated
        $rating3 = new Rating();
        $rating3->rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rating3->rel_id = $content->id;
        $rating3->rating = 4;
        $rating3->session_id = 'session3';
        $rating3->save();

        $newCachedAverage = Rating::getAverage(morph_name(\Modules\Content\Models\Content::class), $content->id);
        $this->assertEquals(4, $newCachedAverage);
    }
}
