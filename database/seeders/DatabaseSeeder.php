<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Organization;
use App\Models\Follow;
use App\Models\Like;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Creating users...');
        
        // Create test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'account_type' => 'personal',
        ]);

        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'account_type' => 'personal',
        ]);

        // Create organization user
        $orgUser = User::factory()->create([
            'name' => 'News Organization',
            'username' => 'newsorg',
            'email' => 'contact@newsorg.com',
            'account_type' => 'organization',
        ]);

        // Create regular users
        $users = User::factory(12)->create();
        $allUsers = collect([$testUser, $adminUser, $orgUser])->merge($users);

        $this->command->info('Creating organizations...');
        
        // Create organizations with the org user as owner
        $organization1 = Organization::factory()->create([
            'owner_id' => $orgUser->id,
            'name' => 'Tech News Daily',
            'slug' => 'tech-news-daily',
        ]);

        $organization2 = Organization::factory()->create([
            'owner_id' => $adminUser->id,
            'name' => 'Global News Network',
            'slug' => 'global-news-network',
        ]);

        // Add specific members to organizations
        $organization1->addMember($users[0], 'admin');
        $organization1->addMember($users[1], 'editor');
        $organization1->addMember($users[2], 'member');

        $organization2->addMember($users[3], 'admin');
        $organization2->addMember($users[4], 'member');

        $this->command->info('Creating posts...');
        
        // Create posts by various users
        foreach ($allUsers->take(10) as $user) {
            Post::factory(rand(1, 4))
                ->published()
                ->create(['user_id' => $user->id]);
        }

        // Create some draft posts for test user
        Post::factory(3)->draft()->create([
            'user_id' => $testUser->id,
        ]);

        $this->command->info('Creating comments and interactions...');
        
        // Get all posts
        $posts = Post::all();

        // Create comments on posts
        foreach ($posts->take(15) as $post) {
            // Create 1-3 top-level comments per post
            $commentCount = rand(1, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                $comment = Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $allUsers->random()->id,
                ]);

                // 30% chance to add a reply
                if (rand(1, 10) <= 3) {
                    Comment::factory()->create([
                        'post_id' => $post->id,
                        'parent_id' => $comment->id,
                        'user_id' => $allUsers->random()->id,
                    ]);
                }
            }
        }

        // Create some follows (safely)
        $followPairs = [
            [$testUser, $adminUser],
            [$testUser, $orgUser],
            [$adminUser, $testUser],
            [$users[0], $testUser],
            [$users[1], $adminUser],
            [$users[2], $orgUser],
            [$users[3], $users[0]],
            [$users[4], $users[1]],
        ];

        foreach ($followPairs as [$follower, $following]) {
            $follower->follow($following);
        }

        // Create some likes on posts
        foreach ($posts->take(10) as $post) {
            $likers = $allUsers->random(rand(1, 5));
            foreach ($likers as $liker) {
                if (!$liker->hasLiked($post)) {
                    $liker->like($post);
                }
            }
        }

        // Create some likes on comments
        $comments = Comment::take(10)->get();
        foreach ($comments as $comment) {
            $likers = $allUsers->random(rand(0, 2));
            foreach ($likers as $liker) {
                if (!$liker->hasLiked($comment)) {
                    $liker->like($comment);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Test user: test@example.com / password');
        $this->command->info('Admin user: admin@example.com / password');
        $this->command->info('Organization user: contact@newsorg.com / password');
    }
}