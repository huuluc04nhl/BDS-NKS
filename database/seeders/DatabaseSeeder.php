<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Demand;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@nks.vn'],
            [
                'name' => 'NKS Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ]
        );

        $user1 = User::updateOrCreate(
            ['email' => 'duyphan@example.com'],
            [
                'name' => 'Duy Phan',
                'password' => bcrypt('123456'),
                'role' => 'renter'
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => 'quocanh@example.com'],
            [
                'name' => 'Quốc Anh',
                'password' => bcrypt('123456'),
                'role' => 'renter'
            ]
        );

        $user3 = User::updateOrCreate(
            ['email' => 'thanhthao@example.com'],
            [
                'name' => 'Thanh Thảo',
                'password' => bcrypt('123456'),
                'role' => 'renter'
            ]
        );

        // 2. Seed Demands (Nhu cầu)
        Demand::create([
            'user_id' => $user1->id,
            'title' => 'Cho thuê căn hộ chung cư Hoàng văn thụ Quận Tân Bình',
            'transaction_type' => 'Thuê',
            'area' => 'Quận Tân Bình, Thành phố Hồ Chí Minh',
            'budget' => '12 triệu/tháng',
            'content' => 'Cần thuê căn hộ chung cư khu vực Hoàng Văn Thụ, Quận Tân Bình, 2 phòng ngủ, sạch sẽ, an ninh, ưu tiên có sẵn một ít nội thất cơ bản.'
        ]);

        Demand::create([
            'user_id' => $user2->id,
            'title' => 'Cho thuê nhà hẻm Long Thuận Quận 9, TP.HCM – 7 triệu/tháng',
            'transaction_type' => 'Thuê',
            'area' => 'Quận 9, Thành phố Hồ Chí Minh',
            'budget' => '7 triệu/tháng',
            'content' => 'Cần thuê nhà nguyên căn trong hẻm rộng khu vực Long Thuận, Quận 9. Yêu cầu có chỗ để xe máy rộng rãi, điện nước giá nhà nước.'
        ]);

        Demand::create([
            'user_id' => $user3->id,
            'title' => 'Cho thuê phòng trọ Đường Đỗ Đốc Chấn Quận Tân Phú',
            'transaction_type' => 'Thuê',
            'area' => 'Quận Tân Phú, Thành phố Hồ Chí Minh',
            'budget' => '3 triệu/tháng',
            'content' => 'Tìm thuê phòng trọ tự do giờ giấc, sạch sẽ tại đường Đỗ Đốc Chấn hoặc lân cận Quận Tân Phú cho sinh viên ở.'
        ]);

        // 3. Seed Videos
        Video::create([
            'title' => 'Căn hộ Landmark 81 Full nội thất view sông',
            'location' => 'Bình Thạnh, TPHCM',
            'type' => 'Cho thuê',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_img' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&q=80&w=600'
        ]);

        Video::create([
            'title' => 'Nhà phố mặt tiền kinh doanh 222 Lê Văn Sỹ',
            'location' => 'Phú Nhuận, TPHCM',
            'type' => 'Bán',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=600'
        ]);

        Video::create([
            'title' => 'Biệt thự song lập compound Thảo Điền Quận 2',
            'location' => 'Quận 2, TPHCM',
            'type' => 'Bán',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_img' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&q=80&w=600'
        ]);

        Video::create([
            'title' => 'Căn hộ Studio dịch vụ tách bếp Phú Nhuận',
            'location' => 'Phú Nhuận, TPHCM',
            'type' => 'Cho thuê',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_img' => 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&q=80&w=600'
        ]);

        // 4. Seed Posts (Bài đăng tin tức)
        Post::create([
            'title' => 'Cách Tối Ưu Hóa Quá Trình Mua Nhà Qua Nền Tảng Online 2026',
            'category' => 'report',
            'summary' => 'Hướng dẫn chi tiết giúp người mua nhà nắm bắt quy trình giao dịch số, thẩm định pháp lý và bản đồ vị trí trực tuyến tối ưu.',
            'feature_img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=600'
        ]);

        Post::create([
            'title' => 'Nhà Đầu Tư Phía Bắc Nam Tiến Thị Trường Bất Động Sản',
            'category' => 'report',
            'summary' => 'Báo cáo xu hướng chuyển dịch dòng vốn đầu tư từ Hà Nội và các tỉnh phía Bắc vào thị trường cho thuê và căn hộ TPHCM.',
            'feature_img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600'
        ]);

        Post::create([
            'title' => 'Các Yếu Tố Ảnh Hưởng Đến Giá Trị Bất Động Sản Năm 2026',
            'category' => 'report',
            'summary' => 'Những thay đổi về quy hoạch hạ tầng đường vành đai, chính sách tín dụng và lãi suất ảnh hưởng trực tiếp đến định giá nhà đất.',
            'feature_img' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&q=80&w=600'
        ]);

        Post::create([
            'title' => 'Hướng Dẫn Đăng Tin Nhà Đất Chuẩn SEO và AI Lên Xu hướng NKS',
            'category' => 'report',
            'summary' => 'Mẹo viết tiêu đề, tối ưu ảnh chụp thực tế và cách điền tọa độ GPS chuẩn xác để đạt tỷ lệ tiếp cận khách hàng cao nhất.',
            'feature_img' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&q=80&w=600'
        ]);

        // Seed some fallback posts for other categories
        Post::create([
            'title' => 'Góc nhìn NKS: Thị trường bất động sản cuối năm 2026 sẽ đi về đâu?',
            'category' => 'view',
            'summary' => 'Phân tích đa chiều về nguồn cung căn hộ dịch vụ và xu hướng giá thuê bất động sản chính chủ.',
            'feature_img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600'
        ]);

        Post::create([
            'title' => 'Xu hướng nội thất tối giản (Minimalism) lên ngôi năm 2026',
            'category' => 'interior',
            'summary' => 'Các mẫu thiết kế giúp tiết kiệm diện tích tối đa cho căn hộ studio nhỏ dưới 35m2.',
            'feature_img' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=600'
        ]);

        Post::create([
            'title' => 'Phong thủy căn hộ chung cư: Cách chọn hướng ban công đón tài lộc',
            'category' => 'fengshui',
            'summary' => 'Bí quyết hóa giải hướng nhà xấu cho gia chủ sinh năm 1990 trở đi khi chọn thuê chung cư.',
            'feature_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=600'
        ]);
    }
}
