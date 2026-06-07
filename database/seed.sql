-- K-MKT Seed Data
-- Default admin: admin / admin123 (เปลี่ยนทันทีหลัง login ครั้งแรก)

SET NAMES utf8mb4;

INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$10$.4gRbMttU1A2CU8ikWgltegX8d7AWL5vD3HD5XvGGxj1dZC.ZgQEi');

INSERT INTO settings (setting_key, setting_value) VALUES
('phone', '081-900-7895'),
('phone_tel', '+66819007895'),
('line_id', '@k-mkt'),
('line_url', 'https://line.me/kmkt'),
('email', 'hello@k-mkt.com'),
('facebook_url', 'https://facebook.com/kmkt.kanchanaburi'),
('messenger_url', 'https://m.me/kmkt'),
('address', 'Kanchanaburi, Thailand');

-- Featured post
INSERT INTO blog_posts (slug, title, excerpt, content, category, image, emoji, read_minutes, is_featured, status, published_at) VALUES
('seo-kanchanaburi-90-days',
 'วิธีทำ SEO ธุรกิจท่องเที่ยวในกาญจนบุรี ให้ติด Google หน้าแรกภายใน 90 วัน',
 'SEO ไม่ใช่เรื่องยากอีกต่อไป ถ้าคุณรู้ว่าควรเริ่มจากตรงไหน บทความนี้จะพาเจ้าของธุรกิจกาญจนบุรีทำ SEO ได้ด้วยตัวเอง ตั้งแต่ Keyword Research จนถึง Content Creation...',
 '<p>SEO สำหรับธุรกิจท่องเที่ยวในกาญจนบุรีต้องเริ่มจากการเข้าใจว่าลูกค้าค้นหาอะไรจริงๆ เช่น "แพกาญจนบุรี" "รีสอร์ทติดแม่น้ำ" "ที่พักไทรโยค"</p><p>ขั้นตอนหลักคือ Keyword Research, On-page SEO, Content ภาษาไทยคุณภาพสูง, Local SEO และ Google Business Profile</p><p>กาญจน์ตลาดช่วยวางแผนและดำเนินการ SEO ครบวงจรให้ธุรกิจกาญจนบุรี ติดต่อปรึกษาฟรีได้ทุกวัน</p>',
 'SEO กาญจนบุรี',
 'assets/images/seo-growth-bright.jpg',
 '',
 8, 1, 'published', '2024-06-01');

INSERT INTO blog_posts (slug, title, excerpt, content, category, emoji, read_minutes, status, published_at) VALUES
('raft-marketing-kanchanaburi', 'แพกาญจนบุรีควรทำการตลาดออนไลน์อย่างไรให้ยอดจองเต็มตลอดปี', 'กลยุทธ์ทำการตลาดสำหรับแพริมน้ำ ดึงลูกค้ากรุงเทพฯ และเมืองใหญ่ให้มาจองตลอดทั้งปี...', '<p>แพริมน้ำกาญจนบุรีต้องโฟกัสทั้ง SEO, Google Map, Meta Ads และ Direct Booking เพื่อลดการพึ่ง OTA</p>', 'การตลาดแพ', '⛵', 6, 'published', '2024-05-15'),
('google-map-resort-kanchanaburi', 'Google Map สำคัญกับรีสอร์ทกาญจนบุรีแค่ไหน และทำอย่างไรให้ติดอันดับ', '70% ของลูกค้าค้นหาที่พักผ่าน Google Maps ก่อนตัดสินใจ นี่คือสิ่งที่คุณต้องรู้...', '<p>Google Business Profile ที่สมบูรณ์ รีวิว 5 ดาว และรูปภาพคุณภาพสูง คือกุญแจสำคัญ</p>', 'Google Map', '🗺️', 5, 'published', '2024-04-20'),
('ai-chatbot-hospitality', 'AI Chatbot ช่วยที่พักกาญจนบุรีตอบลูกค้าและปิดการขายได้อย่างไร', 'เจ้าของที่พักไม่ต้องอยู่หน้าจอตลอดเวลา AI ทำแทนได้ทุกขั้นตอนตั้งแต่ถามราคาจนถึงจอง...', '<p>AI Chatbot บน LINE OA และ Facebook ช่วยตอบคำถาม ส่งโปรโมชั่น และปิดการขาย 24 ชั่วโมง</p>', 'AI Marketing', '🤖', 7, 'published', '2024-04-10'),
('hotel-facebook-ads', 'เจ้าของโรงแรมกาญจนบุรีควรยิงแอด Facebook แบบไหนให้ได้ผลจริง', 'ยิงแอดแล้วไม่ได้ลูกค้า? นี่คือ 5 เหตุผลที่ Facebook Ads ล้มเหลวและวิธีแก้ไข...', '<p>Targeting กลุ่มกรุงเทพฯ ที่วางแผนท่องเที่ยว weekend และใช้ Creative ที่แสดงประสบการณ์จริง</p>', 'การตลาดโรงแรม', '🏨', 6, 'published', '2024-03-18'),
('reduce-ota-dependency', 'วิธีเพิ่มยอดจองที่พักกาญจนบุรีโดยไม่พึ่ง OTA เพียงอย่างเดียว', 'OTA เก็บค่า Commission สูงถึง 20-25% ทำอย่างไรให้ลูกค้าจองตรงกับคุณได้มากขึ้น...', '<p>สร้างเว็บไซต์พร้อม Booking Engine, SEO, และ Remarketing เพื่อดึงลูกค้ากลับมาจองตรง</p>', 'OTA Strategy', '📊', 6, 'published', '2024-03-05'),
('local-seo-explained', 'Local SEO คืออะไร ทำไมธุรกิจท้องถิ่นกาญจนบุรีต้องสนใจ', 'Local SEO เป็นการทำ SEO เฉพาะพื้นที่ ให้ธุรกิจปรากฏเมื่อคนค้นหาใกล้ๆ บ้านคุณ...', '<p>Local Pack, Google Map และ NAP Consistency คือสามเสาหลักของ Local SEO</p>', 'Local SEO', '🏙️', 5, 'published', '2024-02-20'),
('website-importance-2024', 'ทำไมธุรกิจท้องถิ่นกาญจนบุรีต้องมีเว็บไซต์ในปี 2024', 'มีแค่ Facebook Page พอไหม? คำตอบคือไม่พอ เหตุผลว่าทำไมเว็บไซต์ยังสำคัญ...', '<p>เว็บไซต์คือสินทรัพย์ digital ที่คุณเป็นเจ้าของ 100% ต่างจาก Social Media</p>', 'เว็บไซต์', '💻', 5, 'published', '2024-02-10'),
('line-oa-ai-sales', 'LINE OA + AI ช่วยปิดการขายที่พักกาญจนบุรีได้อย่างไรใน 2024', 'ผสม LINE OA กับ AI เพื่อตอบคำถาม ส่งโปรโมชั่น และปิดการขายอัตโนมัติ 24 ชั่วโมง...', '<p>ตั้ง Rich Menu, Broadcast โปรโมชั่น และใช้ AI ตอบคำถามซ้ำๆ อัตโนมัติ</p>', 'LINE OA', '🔗', 6, 'published', '2024-01-25'),
('google-review-tips', '5 วิธีเพิ่มรีวิว Google Map สำหรับธุรกิจกาญจนบุรีแบบถูกกฎหมาย', 'รีวิวดีๆ ช่วยให้ลูกค้าตัดสินใจเลือกคุณได้ง่ายขึ้น นี่คือวิธีที่ถูกต้องในการขอรีวิว...', '<p>ขอรีวิวจากลูกค้าจริงหลังบริการ ผ่าน QR Code และ SMS ห้ามซื้อรีวิวปลอม</p>', 'Google Review', '⭐', 5, 'published', '2024-01-15'),
('tiktok-cafe-marketing', 'ทำ TikTok อย่างไรให้คาเฟ่และร้านอาหารกาญจนบุรีได้ลูกค้าใหม่', 'TikTok เป็นช่องทางฟรีที่ให้ Reach มหาศาล ถ้าทำคลิปได้ถูกต้อง ลูกค้าจะหลั่งไหลมา...', '<p>คลิปสั้น 15-30 วินาที โชว์บรรยากาศและเมนู hit ของร้าน</p>', 'TikTok Marketing', '🎵', 5, 'published', '2023-12-20');

INSERT INTO case_studies (slug, title, subtitle, industry_tag, duration, problem, strategy, quote, quote_author, metrics, status, sort_order) VALUES
('raft-kwai-yai',
 'แพริมน้ำแควใหญ่',
 'เพิ่มยอดจอง 300%',
 '⛵ แพกาญจนบุรี',
 '4 เดือน',
 'แพริมน้ำแควใหญ่ที่ดำเนินการมา 5 ปี พึ่ง OTA 95% มีค่า Commission สูงถึง 20% เว็บไซต์เก่าโหลดช้า Google Map ไม่มีรีวิว Facebook ไม่มีผู้ติดตาม',
 '1. Redesign เว็บไซต์พร้อม Booking Engine 2. ทำ Local SEO + Google Map 3. สร้าง Facebook Content ด้วย AI 4. ยิง Meta Ads เจาะกลุ่มกรุงเทพฯ 5. ติดตั้ง AI Chatbot LINE OA',
 'ก่อนใช้ กาญจน์ตลาด เราไม่รู้เลยว่าจะดึงลูกค้าตรงได้อย่างไร ตอนนี้ 7 เดือน 10 เดือน เต็มหมดแล้ว ไม่ต้องพึ่ง OTA เหมือนเดิม',
 'เจ้าของแพ ย่านไทรโยค',
 '[{"value":"+300%","label":"ยอดจองรวม","color":"gold"},{"value":"+450%","label":"Organic Traffic","color":"blue"},{"value":"70%","label":"Direct Booking","color":"green"},{"value":"180","label":"รีวิว Google ใหม่","color":"gold"}]',
 'published', 1),
('resort-saiyok',
 'รีสอร์ทไทรโยค',
 'Traffic เพิ่ม 500%',
 '🏝️ รีสอร์ท',
 '6 เดือน',
 'รีสอร์ทธรรมชาติที่สวยงามมากแต่ไม่มีใครรู้จักในโลกออนไลน์ เว็บไซต์ไม่มีผู้เข้าชม Google Search ไม่ติดอันดับ Booking มาจาก Word of Mouth เท่านั้น',
 'Full SEO Strategy: Keyword Research, Content Marketing 8 บทความ/เดือน, Link Building จาก Travel Blog, Google Map Optimization',
 '',
 '',
 '[{"value":"+500%","label":"Organic Traffic","color":"gold"},{"value":"Top 3","label":"24 Keywords","color":"blue"},{"value":"+280%","label":"Booking Leads","color":"green"},{"value":"4.9★","label":"Google Rating","color":"gold"}]',
 'published', 2);
