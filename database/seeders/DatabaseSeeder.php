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

        // 4. Seed Enterprises & Projects
        $entVinhomes = \App\Models\Enterprise::updateOrCreate(
            ['slug' => 'vinhomes'],
            [
                'name' => 'Tập đoàn Vinhomes',
                'logo' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=200',
                'address' => 'Tòa nhà văn phòng Symphony, Đường Chu Huy Mân, Khu đô thị Vinhomes Riverside, Phường Phúc Lợi, Quận Long Biên, Hà Nội',
                'phone' => '1900 2323 89',
                'email' => 'info@vinhomes.vn',
                'website' => 'https://vinhomes.vn',
                'description' => 'Vinhomes là thương hiệu bất động sản số 1 Việt Nam, hoạt động trong lĩnh vực phát triển, chuyển nhượng và vận hành bất động sản nhà ở và thương mại phân khúc trung và cao cấp. Các dự án của Vinhomes luôn tiên phong mang đến không gian sống đẳng cấp, tiện ích đồng bộ và môi trường cảnh quan trong lành.',
                'representative' => 'Bà Nguyễn Thu Hằng',
                'tax_code' => '0102671977',
                'founded_year' => 2008
            ]
        );

        $entNovaland = \App\Models\Enterprise::updateOrCreate(
            ['slug' => 'novaland'],
            [
                'name' => 'Tập đoàn Novaland',
                'logo' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=200',
                'address' => 'Tòa nhà văn phòng Novaland, 65 Nguyễn Du, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'phone' => '1900 636666',
                'email' => 'info@novaland.com.vn',
                'website' => 'https://novaland.com.vn',
                'description' => 'Novaland Group là thương hiệu uy tín hàng đầu trong lĩnh vực đầu tư và phát triển bất động sản tại Việt Nam. Trải qua hành trình hơn 30 năm hình thành và phát triển, Novaland hiện sở hữu danh mục hơn 50 dự án nhà ở và đô thị du lịch, kiến tạo cuộc sống hiện đại và thịnh vượng cho hàng triệu cư dân.',
                'representative' => 'Ông Bùi Thành Nhơn',
                'tax_code' => '0301444753',
                'founded_year' => 1992
            ]
        );

        $entDatXanh = \App\Models\Enterprise::updateOrCreate(
            ['slug' => 'dat-xanh'],
            [
                'name' => 'Tập đoàn Đất Xanh',
                'logo' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=200',
                'address' => 'Tòa nhà Đất Xanh Group, 2W Ung Văn Khiêm, Phường 25, Quận Bình Thạnh, TP. Hồ Chí Minh',
                'phone' => '028 6256 3838',
                'email' => 'info@datxanh.com.vn',
                'website' => 'https://datxanh.com.vn',
                'description' => 'Đất Xanh Group là nhà phát triển bất động sản và cung cấp dịch vụ phân phối bất động sản hàng đầu tại thị trường Việt Nam. Tập đoàn luôn nỗ lực cung cấp những sản phẩm nhà ở chất lượng vượt trội cùng dịch vụ tư vấn môi giới chuyên nghiệp nhất.',
                'representative' => 'Ông Lương Trí Thìn',
                'tax_code' => '0303104343',
                'founded_year' => 2003
            ]
        );

        $entNamLong = \App\Models\Enterprise::updateOrCreate(
            ['slug' => 'nam-long'],
            [
                'name' => 'Tập đoàn Nam Long',
                'logo' => 'https://images.unsplash.com/photo-1554469384-e58fac16e23a?auto=format&fit=crop&q=80&w=200',
                'address' => 'Số 6 Nguyễn Khắc Viện, Tân Phú, Quận 7, TP. Hồ Chí Minh',
                'phone' => '028 5416 1718',
                'email' => 'info@namlonggroup.com',
                'website' => 'https://namlonggroup.com',
                'description' => 'Nam Long Group là một trong những nhà phát triển bất động sản tiên phong với hơn 30 năm kinh nghiệm tại Việt Nam. Tập đoàn chuyên phát triển các dự án khu đô thị tích hợp đa tiện ích với các dòng sản phẩm nhà ở thân thiện phù hợp số đông đại chúng.',
                'representative' => 'Ông Trần Xuân Ngọc',
                'tax_code' => '0300755966',
                'founded_year' => 1992
            ]
        );

        $entDaiDuong = \App\Models\Enterprise::updateOrCreate(
            ['slug' => 'dai-duong-group'],
            [
                'name' => 'Công ty TNHH XNK TM và XD Đại Dương',
                'logo' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=200',
                'address' => 'Khu đô thị mới Ocean Park, Gia Lâm, Hà Nội',
                'phone' => '024 3974 8888',
                'email' => 'contact@daiduonggroup.vn',
                'website' => 'https://daiduonggroup.vn',
                'description' => 'Công ty TNHH XNK TM và XD Đại Dương là chủ đầu tư phát triển bất động sản có uy tín lớn, chuyên triển khai xây dựng các dự án hạ tầng giao thông, khu đô thị hiện đại và chung cư cao cấp đạt chuẩn quốc tế.',
                'representative' => 'Ông Phạm Văn Đại',
                'tax_code' => '0105391290',
                'founded_year' => 2011
            ]
        );

        // 5. Seed Posts (Bài đăng tin tức chi tiết có slug và content, sử dụng updateOrCreate tránh lặp)
        Post::updateOrCreate(
            ['slug' => 'cach-toi-uu-hoa-qua-trinh-mua-nha-qua-nen-tang-online-2026'],
            [
                'title' => 'Cách Tối Ưu Hóa Quá Trình Mua Nhà Qua Nền Tảng Online 2026',
                'category' => 'report',
                'summary' => 'Hướng dẫn chi tiết giúp người mua nhà nắm bắt quy trình giao dịch số, thẩm định pháp lý và bản đồ vị trí trực tuyến tối ưu.',
                'feature_img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=600',
                'content' => "## Hướng dẫn giao dịch bất động sản trực tuyến năm 2026\n\nTrong bối cảnh công nghệ số bùng nổ, việc tìm kiếm và mua bán nhà đất online đã trở thành xu hướng tất yếu. Nền tảng **BDS NKS** tiên phong trong việc hỗ trợ định vị bản đồ và công khai mức giá chính chủ.\n\n### 1. Thẩm định pháp lý trực tuyến\nTrước khi xuống tiền đặt cọc trực tuyến, người mua cần kiểm tra kỹ:\n- Giấy chứng nhận quyền sở hữu (Sổ hồng, Sổ đỏ).\n- Bản đồ quy hoạch chi tiết của khu vực.\n- Thông tin doanh nghiệp chủ đầu tư phát triển dự án.\n\n### 2. Sử dụng bản đồ tương tác thông minh\nBản đồ tích hợp MapLibre giúp người mua hình dung được:\n- Vị trí địa lý thực tế của căn hộ.\n- Khoảng cách đến các tiện ích xung quanh (trường học, siêu thị, bệnh viện).\n- Tránh các khu vực kẹt xe hoặc ngập nước vào mùa mưa.\n\nChúc quý khách hàng tìm được ngôi nhà ưng ý nhất qua hệ thống BDS NKS!"
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'nha-dau-tu-phia-bac-nam-tien-thi-truong-bat-dong-san'],
            [
                'title' => 'Nhà Đầu Tư Phía Bắc Nam Tiến Thị Trường Bất Động Sản',
                'category' => 'report',
                'summary' => 'Báo cáo xu hướng chuyển dịch dòng vốn đầu tư từ Hà Nội và các tỉnh phía Bắc vào thị trường cho thuê và căn hộ TPHCM.',
                'feature_img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600',
                'content' => "## Dòng vốn Hà Nội đổ mạnh vào thị trường miền Nam\n\nTheo số liệu báo cáo quý mới nhất của BDS NKS, lượng nhà đầu tư cá nhân từ Hà Nội và các tỉnh phía Bắc tìm kiếm cơ hội đầu tư căn hộ dịch vụ và căn hộ cho thuê tại khu vực TP. Hồ Chí Minh đã tăng vọt 35% so với cùng kỳ năm ngoái.\n\n### Lý do của làn sóng Nam tiến:\n1. **Hiệu suất cho thuê vượt trội:** Tỷ suất lợi nhuận từ việc cho thuê căn hộ tại TPHCM luôn duy trì ở mức ổn định 5.5% - 6.5%/năm, cao hơn đáng kể so với mức 3.8% - 4.5% tại thị trường Hà Nội.\n2. **Giá bán hợp lý hơn:** Nhiều dự án mới bàn giao ở khu vực ven như Quận 9, Thủ Đức có mức giá bán/m² cực kỳ cạnh tranh, tạo tiềm năng sinh lời dài hạn tốt.\n3. **Pháp lý hoàn chỉnh:** Các dự án của các chủ đầu tư uy tín như Vinhomes, Nam Long hay Novaland luôn có tiến độ bàn giao sổ và cam kết vận hành rõ ràng."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'cac-yeu-to-anh-huong-den-gia-tri-bat-dong-san-nam-2026'],
            [
                'title' => 'Các Yếu Tố Ảnh Hưởng Đến Giá Trị Bất Động Sản Năm 2026',
                'category' => 'report',
                'summary' => 'Những thay đổi về quy hoạch hạ tầng đường vành đai, chính sách tín dụng và lãi suất ảnh hưởng trực tiếp đến định giá nhà đất.',
                'feature_img' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&q=80&w=600',
                'content' => "## Các động lực định giá nhà đất trong giai đoạn mới\n\nNăm 2026 chứng kiến những biến động lớn trong chính sách quản lý đất đai của Việt Nam, đặc biệt là việc áp dụng bảng giá đất mới sát giá thị trường. Dưới đây là các yếu tố tác động mạnh nhất:\n\n### 1. Quy hoạch hạ tầng giao thông\nViệc đẩy nhanh tiến độ thi công đường Vành Đai 3, Vành Đai 4 và các tuyến Metro giúp các dự án lân cận tăng giá trị từ 15-20%.\n\n### 2. Chính sách tín dụng và lãi suất vay mua nhà\nLãi suất ưu đãi từ các ngân hàng thương mại được duy trì ổn định giúp kích cầu mua nhà trả góp. Chatbot AI của BDS NKS hỗ trợ tính nhanh lịch trả nợ giúp người mua quản lý tài chính dễ dàng.\n\n### 3. Uy tín của chủ đầu tư dự án\nNgười mua có xu hướng chọn căn hộ của các tập đoàn có tiếng như Novaland, Đất Xanh để đảm bảo chất lượng bàn giao và tiến độ pháp lý sổ hồng."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'huong-dan-dang-tin-nha-dat-chuan-seo-va-ai-len-xu-huong-nks'],
            [
                'title' => 'Hướng Dẫn Đăng Tin Nhà Đất Chuẩn SEO và AI Lên Xu hướng NKS',
                'category' => 'report',
                'summary' => 'Mẹo viết tiêu đề, tối ưu ảnh chụp thực tế và cách điền tọa độ GPS chuẩn xác để đạt tỷ lệ tiếp cận khách hàng cao nhất.',
                'feature_img' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&q=80&w=600',
                'content' => "## Bí quyết đăng tin tiếp cận triệu khách hàng tại BDS NKS\n\nĐể tin đăng của bạn luôn được bộ lọc tìm kiếm ưu tiên và được Trợ lý AI Chatbot gợi ý trước tiên cho khách hàng, hãy áp dụng ngay các mẹo kỹ thuật sau:\n\n### 1. Viết tiêu đề đầy đủ từ khóa vàng\nTiêu đề nên chứa cấu trúc: **[Loại hình] + [Địa chỉ phường/quận] + [Đặc điểm nổi bật/Giá tiền]**.\n*Ví dụ:* \"Cho thuê căn hộ dịch vụ Quận 7 full nội thất ban công rộng - 8 triệu\".\n\n### 2. Hình ảnh chất lượng cao và chân thực\nTải lên tối thiểu 3-5 ảnh chụp thực tế rõ nét của căn hộ. Hệ thống BDS NKS tự động tối ưu hóa dung lượng ảnh để đảm bảo tốc độ tải trang nhanh nhất.\n\n### 3. Khai báo tọa độ GPS chính xác\nĐịnh vị bản đồ chính xác giúp tin đăng hiển thị ngay trên hệ thống MapLibre. Đây cũng là nguồn thông tin cốt lõi để AI giới thiệu tin đăng của bạn khi có khách hỏi thăm khu vực lân cận."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'goc-nhin-nks-thi-truong-bat-dong-san-cuoi-nam-2026-se-di-ve-dau'],
            [
                'title' => 'Góc nhìn NKS: Thị trường bất động sản cuối năm 2026 sẽ đi về đâu?',
                'category' => 'view',
                'summary' => 'Phân tích đa chiều về nguồn cung căn hộ dịch vụ và xu hướng giá thuê bất động sản chính chủ.',
                'feature_img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600',
                'content' => "## Dự báo thị trường căn hộ cho thuê cuối năm 2026\n\nThị trường căn hộ dịch vụ tại TP.HCM đang bước vào giai đoạn cạnh tranh khốc liệt về chất lượng dịch vụ vận hành và chăm sóc khách hàng sau thuê.\n\n### Xu hướng mới:\n- **Căn hộ xanh tiết kiệm năng lượng:** Khách thuê, đặc biệt là người nước ngoài và giới trẻ, sẵn sàng chi trả mức giá cao hơn 10-15% cho các căn hộ có nhiều cây xanh, hệ thống lọc nước sạch và thông gió tự nhiên tốt.\n- **Dịch vụ quản lý thông minh:** Quản lý lịch hẹn xem nhà, đóng tiền nhà và phản ánh sự cố trực tiếp qua nền tảng số hóa như BDS NKS."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'xu-huong-noi-that-toi-gian-minimalism-len-ngoi-nam-2026'],
            [
                'title' => 'Xu hướng nội thất tối giản (Minimalism) lên ngôi năm 2026',
                'category' => 'interior',
                'summary' => 'Các mẫu thiết kế giúp tiết kiệm diện tích tối đa cho căn hộ studio nhỏ dưới 35m2.',
                'feature_img' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=600',
                'content' => "## Thiết kế căn hộ nhỏ tinh tế, hiện đại\n\nPhong cách nội thất tối giản (Minimalism) đang trở thành giải pháp hàng đầu để biến những căn hộ studio nhỏ dưới 35m2 trở nên thoáng đãng, rộng rãi và tràn đầy cảm hứng sống.\n\n### Các nguyên tắc thiết kế tối giản:\n1. **Sử dụng đồ nội thất đa năng:** Giường ngủ tích hợp hộc kéo đựng đồ, bàn ăn gấp gọn thông minh sát tường, sofa kết hợp làm giường ngủ phụ.\n2. **Tông màu sáng trung tính:** Sử dụng màu trắng, kem, hoặc xám nhạt làm chủ đạo giúp phản chiếu ánh sáng tự nhiên tốt nhất.\n3. **Loại bỏ chi tiết thừa:** Chỉ giữ lại những vật dụng thực sự cần thiết, tối ưu không gian lưu trữ ẩn trong hệ tủ kịch trần."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'phong-thuy-can-ho-chung-cu-cach-chon-huong-ban-cong-don-tai-loc'],
            [
                'title' => 'Phong thủy căn hộ chung cư: Cách chọn hướng ban công đón tài lộc',
                'category' => 'fengshui',
                'summary' => 'Bí quyết hóa giải hướng nhà xấu cho gia chủ sinh năm 1990 trở đi khi chọn thuê chung cư.',
                'feature_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=600',
                'content' => "## Hướng phong thủy ban công chung cư rước vượng khí\n\nĐối với căn hộ chung cư cao tầng, hướng ban công đóng vai trò cực kỳ quan trọng trong việc đón nhận luồng khí, ánh sáng và gió tự nhiên thay cho hướng cửa chính.\n\n### Mẹo phong thủy chọn hướng ban công:\n- **Hướng Đông hoặc Đông Nam:** Hướng ban công cát tường nhất, đón nhận ánh bình minh dịu mát của buổi sáng, mang lại sinh khí dồi dào và may mắn cho gia chủ.\n- **Hóa giải ban công hướng Tây (Nóng nực):** Trồng các loại cây xanh tán rộng ưa nắng (hoa giấy, xương rồng, sen đá) và sử dụng rèm che sáng cách nhiệt để tránh luồng sát khí của ánh nắng chiều chiếu trực tiếp vào nhà."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'de-xuat-luat-dat-dai-moi-rut-ngan-thoi-gian-cap-so-hong-15-ngay'],
            [
                'title' => 'Đề Xuất Luật Đất Đai Mới Rút Ngắn Thời Gian Cấp Sổ Hồng Còn 15 Ngày',
                'category' => 'news',
                'summary' => 'Dự thảo mới nhất đề xuất đơn giản hóa quy trình hành chính và chuyển đổi số đồng bộ giúp người dân nhận sổ nhanh chóng.',
                'feature_img' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&q=80&w=600',
                'content' => "## Đột phá cải cách thủ tục hành chính đất đai\n\nBộ Tài nguyên và Môi trường vừa trình Chính phủ dự thảo Nghị định mới hướng dẫn thi hành Luật Đất đai, trong đó điểm nhấn là đề xuất rút ngắn thời hạn cấp giấy chứng nhận quyền sử dụng đất (Sổ hồng, Sổ đỏ) lần đầu xuống còn tối đa 15 ngày làm việc.\n\n### Các điểm mới trong dự thảo:\n- **Liên thông thuế điện tử**: Người dân có thể thực hiện nghĩa vụ tài chính hoàn toàn trực tuyến qua cổng dịch vụ công quốc gia.\n- **Số hóa bản đồ giải thửa**: Giảm thiểu việc đo đạc thủ công tại hiện trường nhờ cơ sở dữ liệu GPS đồng bộ.\n- **Giải quyết hồ sơ liên thông**: Bỏ bớt 3 bước trung gian trong quy trình thẩm định hồ sơ của UBND cấp huyện."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'vinhomes-khoi-cong-sieu-du-an-nha-o-xa-hoi-10000-can-hai-phong'],
            [
                'title' => 'Vinhomes Khởi Công Siêu Dự Án Nhà Ở Xã Hội Quy Mô 10.000 Căn Tại Hải Phòng',
                'category' => 'news',
                'summary' => 'Dự án đáp ứng nhu cầu nhà ở thực cho công nhân và người lao động có thu nhập thấp với đầy đủ tiện ích hiện đại.',
                'feature_img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&q=80&w=600',
                'content' => "## Giải quyết bài toán nhà ở thu nhập thấp tại thành phố cảng\n\nTập đoàn Vinhomes đã chính thức động thổ dự án nhà ở xã hội quy mô lớn nhất từ trước đến nay tại phường Tràng Cát, quận Hải An, thành phố Hải Phòng.\n\n### Quy mô và thiết kế:\n- **Số lượng căn hộ**: Gần 10.000 căn hộ chung cư phân khúc bình dân.\n- **Hạ tầng đồng bộ**: Tích hợp trường học, trạm y tế, công viên cây xanh và khu vui chơi trẻ em.\n- **Mức giá dự kiến**: Chỉ từ 15 - 18 triệu đồng/m², hỗ trợ gói vay ưu đãi lãi suất 4.8%/năm."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'quy-trinh-5-buoc-tham-dinh-phap-ly-khi-mua-nha-dat-chua-co-so'],
            [
                'title' => 'Quy Trình 5 Bước Thẩm Định Pháp Lý Khi Mua Nhà Đất Chưa Có Sổ',
                'category' => 'knowledge',
                'summary' => 'Cẩm nang bỏ túi giúp người mua nhà tránh bẫy tranh chấp, đất dính quy hoạch hoặc lừa đảo mua bán giấy tay.',
                'feature_img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=600',
                'content' => "## Hướng dẫn an toàn pháp lý khi mua nhà đất chưa ra sổ\n\nMua nhà đất chưa có sổ đỏ luôn tiềm ẩn rủi ro cực kỳ lớn. Để tự bảo vệ dòng tiền đầu tư, bạn cần thực hiện đầy đủ 5 bước thẩm định nghiêm ngặt dưới đây:\n\n### Quy trình thẩm định 5 bước:\n1. **Kiểm tra thông tin quy hoạch**: Lên Văn phòng Đăng ký Đất đai cấp quận/huyện để xin trích lục thông tin quy hoạch mới nhất.\n2. **Xác minh tranh chấp thực tế**: Hỏi thăm hàng xóm xung quanh và UBND phường xã xem khu đất có đang bị khiếu nại hay tranh chấp ranh giới không.\n3. **Kiểm tra nguồn gốc đất**: Đối chiếu kỹ tờ bản đồ giải thửa, tờ khai lệ phí trước bạ và hồ sơ cấp số nhà của các đời chủ trước.\n4. **Công chứng vi bằng**: Dù hợp đồng mua bán giấy tay không có giá trị pháp lý chuyển nhượng, việc lập vi bằng tại Văn phòng Thừa phát lại sẽ giúp chứng minh có giao dịch tiền thực tế.\n5. **Giữ lại một phần tiền**: Chỉ thanh toán tối đa 70% giá trị giao dịch, phần còn lại thanh toán sau khi hoàn tất thủ tục bàn giao nhà và nộp hồ sơ xin cấp sổ."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'so-hong-chung-la-gi-co-nen-xuong-tien-mua-can-ho-so-chung-khong'],
            [
                'title' => 'Sổ Hồng Chung Là Gì? Có Nên Xuống Tiền Mua Căn Hộ Sổ Chung Không?',
                'category' => 'knowledge',
                'summary' => 'Phân tích ưu nhược điểm, rủi ro pháp lý và khả năng thanh khoản của loại hình bất động sản đồng sở hữu.',
                'feature_img' => 'https://images.unsplash.com/photo-1543087903-1ac2ec7aa8c5?auto=format&fit=crop&q=80&w=600',
                'content' => "## Phân tích pháp lý loại hình bất động sản đồng sở hữu\n\nSổ hồng chung (hay sổ hồng đồng sở hữu) là loại giấy chứng nhận quyền sở hữu nhà đất cấp cho từ 2 cá nhân không có quan hệ vợ chồng, con cái chung cùng đứng tên trên sổ.\n\n### Những rủi ro cần lưu ý:\n- **Thủ tục chuyển nhượng phức tạp**: Khi muốn bán hoặc thế chấp, bắt buộc phải có sự đồng ý bằng văn bản ký tên của tất cả các đồng sở hữu. Chỉ cần một người không đồng ý, giao dịch sẽ bị phong tỏa.\n- **Không được ngân hàng hỗ trợ vay**: Hầu hết ngân hàng lớn đều từ chối nhận thế chấp tài sản sổ hồng chung do tính chất tranh chấp phức tạp.\n- **Khó xây dựng sửa chữa**: Xin giấy phép xây dựng hoặc sửa chữa trên đất sổ chung đòi hỏi thủ tục rất rườm rà."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'cach-thiet-ke-ban-cong-chung-cu-thanh-khu-vuon-thu-gian-xanh-mat'],
            [
                'title' => 'Cách Thiết Kế Ban Công Chung Cư Thành Khu Vườn Thư Giãn Xanh Mát',
                'category' => 'interior',
                'summary' => 'Gợi ý chọn cây xanh chịu nắng, lót sàn gỗ tự nhiên và bố trí đèn LED chill ấm áp cho ban công nhỏ.',
                'feature_img' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=600',
                'content' => "## Biến ban công nhỏ thành nơi thư giãn lý tưởng\n\nBan công chung cư dù nhỏ hẹp vẫn có thể lột xác thành góc uống trà, đọc sách tuyệt vời nếu bạn biết cách bố trí hợp lý.\n\n### Các bước thực hiện:\n1. **Lót sàn vỉ gỗ nhựa composite**: Vật liệu chịu nước tốt, dễ lắp ráp và mang lại cảm giác ấm cúng như gỗ tự nhiên.\n2. **Trồng cây xanh giật cấp**: Sử dụng giá treo sắt trên lan can để treo các chậu cây dây leo (cúc tần, vạn niên thanh) kết hợp chậu cây đặt sàn (lưỡi hổ, trầu bà).\n3. **Chọn bàn ghế xếp gọn**: Bàn ghế gỗ hoặc mây tre có thể gấp lại khi cần phơi đồ hoặc lau dọn ban công.\n4. **Hệ thống chiếu sáng**: Sử dụng dây đèn LED vàng chống nước chạy dọc lan can tạo không gian lung linh vào buổi tối."
            ]
        );

        Post::updateOrCreate(
            ['slug' => '5-loai-cay-phong-thuy-dat-ban-lam-viec-giup-gia-chu-hut-tai-loc'],
            [
                'title' => '5 Loại Cây Phong Thủy Đặt Bàn Làm Việc Giúp Gia Chủ Hút Tài Lộc',
                'category' => 'fengshui',
                'summary' => 'Cách chọn cây hợp mệnh Kim, Mộc, Thủy, Hỏa, Thổ giúp tăng vượng khí, công việc hanh thông.',
                'feature_img' => 'https://images.unsplash.com/photo-1501183007986-d0d080b147f9?auto=format&fit=crop&q=80&w=600',
                'content' => "## Cây xanh để bàn kích hoạt năng lượng tài lộc\n\nĐặt cây phong thủy trên bàn làm việc không chỉ giúp thanh lọc không khí mà còn có tác dụng thu hút may mắn, thúc đẩy sự nghiệp phát triển.\n\n### Danh sách 5 loại cây chiêu tài hàng đầu:\n- **Cây Kim Tiền**: Lá tròn múp mang năng lượng Kim, đại diện cho tiền tài, thích hợp với tất cả các mệnh.\n- **Cây Vạn Lộc**: Lá đỏ hồng nổi bật, mang lại sự thịnh vượng, cực kỳ hợp với người mệnh Hỏa và mệnh Thổ.\n- **Cây Kim Ngân**: Thân xoắn bện vững chắc, thu hút tiền tài và giữ của cải cho gia chủ.\n- **Cây Trầu Bà Đế Vương**: Lá to rộng sang trọng, thể hiện quyền lực và ý chí tiến thủ lớn.\n- **Cây Sen Đá Nâu**: Nhỏ gọn dễ chăm sóc, hóa giải năng lượng tiêu cực xung quanh bàn làm việc."
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'goc-nhin-nks-can-ho-studio-quan-7-chiem-linh-phan-khuc-cho-thue'],
            [
                'title' => 'Góc Nhìn NKS: Căn Hộ Studio Quận 7 Đang Dần Chiếm Lĩnh Phân Khúc Cho Thuê',
                'category' => 'view',
                'summary' => 'Phân tích xu hướng lựa chọn không gian sống độc lập, tiện ích cao cấp của thế hệ Gen Z và người đi làm độc thân.',
                'feature_img' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=600',
                'content' => "## Sự lên ngôi của không gian sống cá nhân hóa tại Nam Sài Gòn\n\nPhân khúc căn hộ studio diện tích từ 28 - 35m² tại khu vực Quận 7, TP.HCM đang ghi nhận tỷ lệ lấp đầy ấn tượng lên tới 92% trong năm 2026.\n\n### Tại sao Gen Z ưu tiên Căn hộ Studio?\n- **Chi phí thuê hợp lý**: Dao động từ 6 - 8 triệu đồng/tháng, phù hợp ngân sách của người đi làm có thu nhập ổn định.\n- **Tiện ích khép kín**: Tích hợp phòng ngủ, bếp và bàn làm việc trong cùng một không gian mở tinh gọn.\n- **Vị trí thuận lợi**: Gần các trường đại học lớn (RMIT, Tôn Đức Thắng) và các tòa nhà văn phòng tại Phú Mỹ Hưng."
            ]
        );
    }
}
