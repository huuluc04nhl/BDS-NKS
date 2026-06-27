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

        // 5. Seed Posts (12 bài viết, 4 danh mục x 3 bài, updateOrCreate tránh lặp)
        // Danh mục: report (Báo cáo thị trường), news (Tin tức BĐS), interior (Nội thất), fengshui (Phong thủy)

        // === BÁO CÁO THỊ TRƯỜNG (report) ===
        Post::updateOrCreate(['slug' => 'cach-toi-uu-hoa-qua-trinh-mua-nha-qua-nen-tang-online-2026'], [
            'title' => 'Cách Tối Ưu Hóa Quá Trình Mua Nhà Qua Nền Tảng Online 2026',
            'category' => 'report',
            'summary' => 'Hướng dẫn chi tiết giúp người mua nhà nắm bắt quy trình giao dịch số, thẩm định pháp lý và bản đồ vị trí trực tuyến tối ưu.',
            'feature_img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=600',
            'content' => "## Hướng dẫn giao dịch bất động sản trực tuyến năm 2026\n\nTrong bối cảnh công nghệ số bùng nổ, việc tìm kiếm và mua bán nhà đất online đã trở thành xu hướng tất yếu.\n\n### 1. Thẩm định pháp lý trực tuyến\nTrước khi xuống tiền đặt cọc, người mua cần kiểm tra kỹ:\n- Giấy chứng nhận quyền sở hữu (Sổ hồng, Sổ đỏ).\n- Bản đồ quy hoạch chi tiết của khu vực.\n- Thông tin doanh nghiệp chủ đầu tư phát triển dự án.\n\n### 2. Sử dụng bản đồ tương tác thông minh\nBản đồ tích hợp MapLibre giúp người mua hình dung được:\n- Vị trí địa lý thực tế của căn hộ.\n- Khoảng cách đến các tiện ích xung quanh.\n- Tránh các khu vực kẹt xe hoặc ngập nước."
        ]);

        Post::updateOrCreate(['slug' => 'nha-dau-tu-phia-bac-nam-tien-thi-truong-bat-dong-san'], [
            'title' => 'Nhà Đầu Tư Phía Bắc Nam Tiến Thị Trường Bất Động Sản',
            'category' => 'report',
            'summary' => 'Báo cáo xu hướng chuyển dịch dòng vốn đầu tư từ Hà Nội và các tỉnh phía Bắc vào thị trường cho thuê và căn hộ TPHCM.',
            'feature_img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600',
            'content' => "## Dòng vốn Hà Nội đổ mạnh vào thị trường miền Nam\n\nTheo số liệu báo cáo quý mới nhất, lượng nhà đầu tư cá nhân từ Hà Nội tìm kiếm cơ hội đầu tư căn hộ dịch vụ tại TP.HCM đã tăng vọt 35%.\n\n### Lý do của làn sóng Nam tiến:\n1. **Hiệu suất cho thuê vượt trội:** Tỷ suất lợi nhuận 5.5% - 6.5%/năm.\n2. **Giá bán hợp lý hơn:** Nhiều dự án mới bàn giao ở Quận 9, Thủ Đức có mức giá rất cạnh tranh.\n3. **Pháp lý hoàn chỉnh:** Các chủ đầu tư uy tín luôn có tiến độ bàn giao sổ rõ ràng."
        ]);

        Post::updateOrCreate(['slug' => 'cac-yeu-to-anh-huong-den-gia-tri-bat-dong-san-nam-2026'], [
            'title' => 'Các Yếu Tố Ảnh Hưởng Đến Giá Trị Bất Động Sản Năm 2026',
            'category' => 'report',
            'summary' => 'Những thay đổi về quy hoạch hạ tầng đường vành đai, chính sách tín dụng và lãi suất ảnh hưởng trực tiếp đến định giá nhà đất.',
            'feature_img' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&q=80&w=600',
            'content' => "## Các động lực định giá nhà đất trong giai đoạn mới\n\nNăm 2026 chứng kiến những biến động lớn trong chính sách quản lý đất đai.\n\n### 1. Quy hoạch hạ tầng giao thông\nViệc đẩy nhanh tiến độ thi công đường Vành Đai 3, Vành Đai 4 và các tuyến Metro giúp các dự án lân cận tăng giá trị từ 15-20%.\n\n### 2. Chính sách tín dụng và lãi suất vay mua nhà\nLãi suất ưu đãi từ các ngân hàng thương mại được duy trì ổn định giúp kích cầu mua nhà trả góp.\n\n### 3. Uy tín của chủ đầu tư dự án\nNgười mua có xu hướng chọn căn hộ của các tập đoàn có tiếng như Novaland, Đất Xanh."
        ]);

        // === TIN TỨC BĐS (news) ===
        Post::updateOrCreate(['slug' => 'can-ho-phia-dong-ha-noi-chi-tu-50-trieu-dong-m2'], [
            'title' => 'Căn hộ phía Đông Hà Nội chỉ từ 50 triệu đồng/m² cho gia đình trẻ',
            'category' => 'news',
            'summary' => 'Khu vực phía Đông Hà Nội đang nổi lên với nhiều dự án căn hộ mức giá hợp lý, phù hợp với các gia đình trẻ có ngân sách tầm trung.',
            'feature_img' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&q=80&w=600',
            'content' => "## Cơ hội sở hữu nhà cho gia đình trẻ tại phía Đông Hà Nội\n\nPhía Đông Hà Nội đang trở thành tâm điểm nhờ hạ tầng giao thông phát triển vượt bậc và mức giá căn hộ còn hợp lý.\n\n### Các dự án nổi bật:\n- **Vinhomes Ocean Park 2 & 3:** Giá từ 50-65 triệu/m².\n- **Masteri Waterfront:** Căn hộ cao cấp view biển hồ nhân tạo.\n- **Eurowindow River Park:** Giá khởi điểm chỉ từ 1.5 tỷ/căn 2PN.\n\n### Lợi thế hạ tầng:\nCầu Vĩnh Tuy 2 và đường Vành Đai 4 đang được đẩy nhanh tiến độ, rút ngắn thời gian di chuyển về trung tâm Hà Nội chỉ còn 20-25 phút."
        ]);

        Post::updateOrCreate(['slug' => 'lai-suat-cho-vay-mua-nha-thang-6-2026-ngan-hang-nao-thap-nhat'], [
            'title' => 'Lãi suất cho vay mua nhà tháng 6/2026: Ngân hàng nào thấp nhất?',
            'category' => 'news',
            'summary' => 'So sánh chi tiết lãi suất cho vay mua nhà từ 15 ngân hàng lớn tại Việt Nam.',
            'feature_img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&q=80&w=600',
            'content' => "## Bảng so sánh lãi suất vay mua nhà tháng 6/2026\n\nViệc lựa chọn ngân hàng có mức lãi suất phù hợp là yếu tố then chốt giúp người mua nhà tiết kiệm hàng trăm triệu đồng.\n\n### Top 5 ngân hàng lãi suất thấp nhất:\n1. **Vietcombank:** 6.5%/năm (ưu đãi 12 tháng đầu).\n2. **Techcombank:** 6.8%/năm (cố định 24 tháng đầu).\n3. **BIDV:** 7.0%/năm, hỗ trợ giải ngân nhanh trong 48h.\n4. **VPBank:** 6.9%/năm (gói Dream Home), vay tối đa 30 năm.\n5. **MB Bank:** 7.2%/năm, miễn phí thẩm định.\n\n### Lưu ý khi vay mua nhà:\n- Tổng trả hàng tháng không nên vượt quá **40% thu nhập ròng** gia đình.\n- Ưu tiên lãi suất cố định dài hạn (24-36 tháng)."
        ]);

        Post::updateOrCreate(['slug' => 'tp-hcm-tang-toc-cap-so-hong-cho-hang-ngan-can-ho'], [
            'title' => 'TP.HCM tăng tốc cấp sổ hồng cho hàng ngàn căn hộ trong năm 2026',
            'category' => 'news',
            'summary' => 'UBND TP.HCM chỉ đạo đẩy nhanh tiến trình cấp giấy chứng nhận quyền sử dụng nhà ở cho nhiều dự án tồn đọng pháp lý.',
            'feature_img' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&q=80&w=600',
            'content' => "## Bước ngoặt pháp lý cho hàng ngàn cư dân TP.HCM\n\nSau nhiều năm chờ đợi, hàng ngàn cư dân tại các dự án chung cư lớn ở TP.HCM sắp được nhận sổ hồng.\n\n### Các dự án được ưu tiên:\n- **The Sun Avenue (Quận 2):** 1,246 căn hộ dự kiến được cấp sổ trong quý III/2026.\n- **Saigon Mia (Bình Chánh):** 872 căn, hoàn tất thủ tục nghĩa vụ tài chính.\n- **Richmond City (Nguyễn Xí):** 518 căn, đã nộp đủ hồ sơ pháp lý.\n\n### Tác động đến thị trường:\nViệc cấp sổ hồng hàng loạt sẽ tạo nguồn cung thanh khoản mới, giúp các chủ sở hữu có thể chuyển nhượng, thế chấp ngân hàng hoặc ký hợp đồng cho thuê dài hạn hợp pháp."
        ]);

        // === NỘI THẤT (interior) ===
        Post::updateOrCreate(['slug' => 'xu-huong-noi-that-toi-gian-minimalism-len-ngoi-nam-2026'], [
            'title' => 'Xu hướng nội thất tối giản (Minimalism) lên ngôi năm 2026',
            'category' => 'interior',
            'summary' => 'Các mẫu thiết kế giúp tiết kiệm diện tích tối đa cho căn hộ studio nhỏ dưới 35m2.',
            'feature_img' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=600',
            'content' => "## Thiết kế căn hộ nhỏ tinh tế, hiện đại\n\nPhong cách nội thất tối giản đang trở thành giải pháp hàng đầu để biến căn hộ studio nhỏ dưới 35m2 trở nên thoáng đãng.\n\n### Nguyên tắc thiết kế tối giản:\n1. **Đồ nội thất đa năng:** Giường tích hợp hộc kéo, bàn ăn gấp gọn, sofa giường.\n2. **Tông màu sáng trung tính:** Trắng, kem, xám nhạt phản chiếu ánh sáng tự nhiên.\n3. **Loại bỏ chi tiết thừa:** Chỉ giữ vật dụng cần thiết, tối ưu lưu trữ ẩn trong tủ kịch trần."
        ]);

        Post::updateOrCreate(['slug' => 'thiet-ke-phong-khach-lien-bep-xu-huong-open-concept-2026'], [
            'title' => 'Thiết kế phòng khách liên bếp: Xu hướng Open Concept 2026',
            'category' => 'interior',
            'summary' => 'Bố trí không gian mở liên thông phòng khách - bếp giúp tối ưu diện tích và tạo cảm giác rộng rãi cho căn hộ chung cư.',
            'feature_img' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&q=80&w=600',
            'content' => "## Open Concept - Không gian mở cho căn hộ hiện đại\n\nXu hướng Open Concept đang được ưa chuộng tại các dự án căn hộ mới, phù hợp lối sống năng động.\n\n### Ưu điểm:\n- **Tăng tương tác gia đình:** Nấu ăn vẫn trông con chơi ở phòng khách.\n- **Tận dụng ánh sáng tự nhiên:** Không vách ngăn cứng, ánh sáng tràn vào toàn bộ.\n- **Linh hoạt bố trí:** Dễ dàng thay đổi layout nội thất.\n\n### Giải pháp khử mùi bếp:\n1. Lắp máy hút mùi công suất lớn (trên 1000m3/h).\n2. Sử dụng bếp từ thay bếp gas giảm khói.\n3. Trồng cây lọc không khí: trầu bà, lưỡi hổ."
        ]);

        Post::updateOrCreate(['slug' => 'top-5-phong-cach-noi-that-duoc-yeu-thich-nhat-2026'], [
            'title' => 'Top 5 phong cách nội thất được yêu thích nhất năm 2026',
            'category' => 'interior',
            'summary' => 'Điểm qua 5 phong cách thiết kế nội thất đang thống trị: Japandi, Industrial, Scandinavian, Wabi-Sabi và Neo-Classic.',
            'feature_img' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&q=80&w=600',
            'content' => "## 5 Phong cách nội thất dẫn đầu xu hướng 2026\n\nViệc lựa chọn phong cách nội thất phù hợp không chỉ mang lại không gian sống thoải mái mà còn thể hiện gu thẩm mỹ riêng.\n\n### 1. Japandi (Nhật Bản + Scandinavian)\nKết hợp sự thanh lịch tối giản Nhật Bản và sự ấm áp Bắc Âu. Gỗ tự nhiên, tông trung tính.\n\n### 2. Industrial (Công nghiệp)\nTường gạch trần, ống thép lộ thiên, bê tông mài. Phù hợp căn hộ trần cao.\n\n### 3. Scandinavian (Bắc Âu)\nTông trắng chủ đạo, gỗ sáng màu, cây xanh. Ưu tiên ánh sáng tự nhiên.\n\n### 4. Wabi-Sabi\nTriết lý vẻ đẹp không hoàn hảo. Gốm sứ thủ công, vải linen, gỗ mộc.\n\n### 5. Neo-Classic (Tân cổ điển)\nSang trọng cổ điển Châu Âu được hiện đại hóa. Phù hợp penthouse, biệt thự."
        ]);

        // === PHONG THỦY (fengshui) ===
        Post::updateOrCreate(['slug' => 'phong-thuy-can-ho-chung-cu-cach-chon-huong-ban-cong-don-tai-loc'], [
            'title' => 'Phong thủy căn hộ chung cư: Cách chọn hướng ban công đón tài lộc',
            'category' => 'fengshui',
            'summary' => 'Bí quyết hóa giải hướng nhà xấu cho gia chủ sinh năm 1990 trở đi khi chọn thuê chung cư.',
            'feature_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=600',
            'content' => "## Hướng phong thủy ban công chung cư rước vượng khí\n\nĐối với căn hộ chung cư cao tầng, hướng ban công đóng vai trò cực kỳ quan trọng trong việc đón nhận luồng khí và ánh sáng.\n\n### Mẹo chọn hướng ban công:\n- **Hướng Đông hoặc Đông Nam:** Đón ánh bình minh, sinh khí dồi dào.\n- **Hóa giải hướng Tây:** Trồng cây xanh tán rộng và sử dụng rèm che sáng cách nhiệt."
        ]);

        Post::updateOrCreate(['slug' => 'chon-tang-so-can-ho-theo-phong-thuy-ngu-hanh-menh'], [
            'title' => 'Chọn tầng số căn hộ theo phong thủy ngũ hành mệnh',
            'category' => 'fengshui',
            'summary' => 'Hướng dẫn chọn tầng chung cư phù hợp mệnh Kim, Mộc, Thủy, Hỏa, Thổ để gia đình luôn thuận lợi, may mắn.',
            'feature_img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&q=80&w=600',
            'content' => "## Bí quyết chọn tầng chung cư hợp mệnh ngũ hành\n\nTheo phong thủy, mỗi con số tầng mang một năng lượng riêng. Chọn đúng tầng giúp gia đạo thuận hòa.\n\n### Bảng tra cứu tầng theo mệnh:\n- **Mệnh Kim:** Tầng 4, 9, 14, 19. Tài vận thịnh vượng.\n- **Mệnh Mộc:** Tầng 3, 8, 13, 18. Sinh khí dồi dào.\n- **Mệnh Thủy:** Tầng 1, 6, 11, 16. Tài lộc dồi dào.\n- **Mệnh Hỏa:** Tầng 2, 7, 12, 17. Nhiệt huyết, thăng tiến nhanh.\n- **Mệnh Thổ:** Tầng 5, 10, 15, 20. Ổn định, bền vững.\n\n### Lưu ý:\nNgoài mệnh ngũ hành, cần xem xét thêm hướng cửa chính và hướng ban công."
        ]);

        Post::updateOrCreate(['slug' => 'cach-dat-ban-tho-chung-cu-dung-phong-thuy-2026'], [
            'title' => 'Cách đặt bàn thờ chung cư đúng phong thủy 2026',
            'category' => 'fengshui',
            'summary' => 'Hướng dẫn chi tiết vị trí, hướng đặt và những kiêng kỵ khi bố trí bàn thờ tại căn hộ chung cư.',
            'feature_img' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&q=80&w=600',
            'content' => "## Bố trí bàn thờ chung cư theo chuẩn phong thủy\n\nViệc bố trí bàn thờ đúng vị trí và hướng là điều nhiều gia chủ quan tâm nhất khi về nhà mới.\n\n### Nguyên tắc vàng:\n1. **Vị trí:** Đặt ở phòng khách, hướng ra cửa chính hoặc ban công. Tránh dưới xà ngang.\n2. **Chiều cao:** Tối thiểu 1.5m tính từ sàn.\n3. **Hướng:** Ưu tiên hướng Nam hoặc Đông Nam.\n\n### Kiêng kỵ:\n- Không đặt đối diện nhà vệ sinh hoặc phòng ngủ.\n- Không để đồ vật linh tinh, giày dép dưới bàn thờ.\n- Thường xuyên lau dọn, thay nước và hoa tươi mỗi tuần."
        ]);
    }
}
