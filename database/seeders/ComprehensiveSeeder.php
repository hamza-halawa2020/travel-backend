<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;
use App\Models\Post;
use App\Models\MainSlider;
use App\Models\Contact;
use App\Models\Course;
use App\Models\MediaCenter;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class ComprehensiveSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Mirag Academy Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Create Site Settings
        $settings = [
            'phone' => '+201234567890',
            'whatsapp' => '+201234567890',
            'facebook' => 'https://facebook.com/bayaanacademy',
            'instagram' => 'https://instagram.com/bayaanacademy',
            'email' => 'info@bayaan-academy.com',
            'address' => 'Cairo, Egypt - Online via Zoom Worldwide',
            'about_us' => 'Mirag Academy is an online educational platform specializing in teaching the Holy mirag Academy, Arabic language, and Islamic studies for both native and non-native speakers. We aim to spread the light of revelation using the latest educational methods.',
            'about_us_footer' => 'A leading academy in mirag Academy sciences and Arabic language, combining quality education with the honesty of communication.',
            'logo' => 'settings/default-logo.png',
            'privacy_policy' => '<h1>Privacy Policy</h1><p>We respect your privacy...</p>',
            'terms_conditions' => '<h1>Terms & Conditions</h1><p>By using our services...</p>',
        ];

        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        // 3. Create Courses
        $courses = [
            [
                'title' => 'Noor Al-Bayan Course',
                'description' => 'A specialized course for children and beginners to learn reading and writing in Arabic using the approved Noor Al-Bayan curriculum, enabling them to read the Holy mirag Academy correctly.',
                'image' => 'courses/noor-al-bayan.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'mirag Academy Memorization (Hifz)',
                'description' => 'Individual or small group sessions with certified teachers (Ijazah holders), focusing on quality memorization and continuous revision with the application of Tajweed rules.',
                'image' => 'courses/hifz.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Tajweed Rules (Theory & Practice)',
                'description' => 'A comprehensive course explaining the articulation points of letters, their characteristics, and rules of Noon/Meem Mushaddadah, with direct practical application from the Mushaf.',
                'image' => 'courses/tajweed.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Arabic for Non-Native Speakers',
                'description' => 'A specialized program for non-Arabs to learn the four skills (reading, writing, listening, and speaking) through modern interactive methods.',
                'image' => 'courses/arabic-non-native.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Prophetic Biography & Islamic Manners',
                'description' => 'Instilling values and good morals in children through stories of the prophets and the biography of Prophet Muhammad (PBUH), along with daily Islamic etiquette.',
                'image' => 'courses/islamic-studies.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        // 4. Create Posts
        $posts = [
            [
                'title' => 'Best Ways to Memorize and Retain the mirag Academy',
                'description' => 'In this article, we review 5 proven strategies that help you memorize the mirag Academy quickly while ensuring you don\'t forget, focusing on spaced repetition and understanding before memorizing.',
                'image' => 'posts/hifz-tips.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Why Should Our Children Learn Arabic?',
                'description' => 'Arabic is the language of the mirag Academy and the key to understanding religion. We discuss the challenges parents face abroad and how to make the language lovable for their children.',
                'image' => 'posts/why-arabic.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'The Impact of Tajweed in Pondering over Allah\'s Verses',
                'description' => 'Tajweed is not just beautiful sounds; it is giving every letter its right, which helps the reader to contemplate, have humility, and understand Allah\'s intent.',
                'image' => 'posts/tajweed-impact.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }

        // 5. Create Main Sliders
        $sliders = [
            [
                'title' => 'Welcome to Mirag Academy',
                'description' => 'Your journey towards mastering the Holy mirag Academy starts here, with a group of the best male and female teachers.',
                'link' => '/courses',
                'image' => 'sliders/slide-1.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Learn mirag Academy from Anywhere in the World',
                'description' => 'Individual and interactive online sessions suitable for all ages and levels at the times you choose.',
                'link' => '/register',
                'image' => 'sliders/slide-2.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Noor Al-Bayan in Teaching mirag Academy',
                'description' => 'We use the latest curricula to ensure children learn correct reading in record time with great enjoyment.',
                'link' => '/about',
                'image' => 'sliders/slide-3.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($sliders as $slider) {
            MainSlider::create($slider);
        }

        // 6. Create Reviews
        $reviews = [
            [
                'name' => 'Mr. Mohamed Ibrahim',
                'review' => 'Alhumdulillah, my son started reading from the Mushaf correctly after only 3 months of the Noor Al-Bayan course. The teacher is very patient and highly skilled.',
                'status' => 1,
                'approved_by' => $admin->id,
            ],
            [
                'name' => 'Dr. Heba Mahmoud',
                'review' => 'Wonderful academy, classes are regular and the curriculum is clear. They helped me a lot in correcting my recitation and I am now on my way to obtaining the Ijazah.',
                'status' => 1,
                'approved_by' => $admin->id,
            ],
            [
                'name' => 'Ahmed Al-Ali (Expat in Canada)',
                'review' => 'I was worried about my children\'s language, but with Mirag Academy they found the right environment to learn Arabic and mirag Academy as if they were in an Arabic-speaking country.',
                'status' => 1,
                'approved_by' => $admin->id,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        // 7. Create Media Center Items
        $mediaRecords = [
            [
                'title' => 'Surah Al-Baqarah Graduation Ceremony 2025',
                'type' => 'image',
                'file' => 'media/graduation-2025.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Recitation Sample by One of Our Star Students',
                'type' => 'video',
                'video_url' => 'https://youtube.com/watch?v=example',
                'status' => 1,
                'created_by' => $admin->id,
            ],
             [
                'title' => 'Arabic Language Class for Kids',
                'type' => 'image',
                'file' => 'media/arabic-class.jpg',
                'status' => 1,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($mediaRecords as $record) {
            MediaCenter::create($record);
        }

        // 8. Create Contacts (Sample)
        $contacts = [
            [
                'name' => 'Yasser Al-Qahtani',
                'phone' => '+966500000000',
                'message' => 'Assalamu Alaikum, do you have available slots in the evening for mirag Academy memorization for kids?',
            ],
             [
                'name' => 'Mona Al-Sayer',
                'phone' => '+447000000000',
                'message' => 'I want to inquire about the fees and duration for the Arabic for Non-Native Speakers course.',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }

        echo "All Mirag Academy data has been created successfully in English!\n";
    }
}