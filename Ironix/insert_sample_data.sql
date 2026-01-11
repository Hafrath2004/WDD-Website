-- Sample Data for Ironix Hardware Shop
-- Products Table: 20 products
-- Blog Table: 5 blog posts with product details

-- Insert 20 Products
INSERT INTO products (name, description, price, image_url) VALUES
('Professional Hammer Set', 'Heavy-duty hammer set with fiberglass handles. Includes claw hammer, ball peen hammer, and rubber mallet. Perfect for construction and DIY projects.', 3500.00, 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500'),
('Cordless Drill Driver Kit', '18V lithium-ion cordless drill with 2 batteries, charger, and 50-piece bit set. Variable speed control and LED work light included.', 12500.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Adjustable Wrench Set', 'Premium chrome-plated adjustable wrench set (6", 8", 10", 12"). Non-slip grip handles for maximum torque and comfort.', 2800.00, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500'),
('Screwdriver Set 20-Piece', 'Professional screwdriver set with magnetic tips. Includes Phillips, flathead, Torx, and hex drivers. Ergonomic handles for extended use.', 2200.00, 'https://images.unsplash.com/photo-1625753783470-1e3e5b6e5b5e?w=500'),
('Circular Saw 7.25"', 'Heavy-duty circular saw with laser guide and dust blower. 15-amp motor delivers 5,800 RPM. Includes blade and carrying case.', 18500.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Measuring Tape 5m', 'Professional steel measuring tape with magnetic tip. Auto-lock mechanism and impact-resistant case. Metric and imperial measurements.', 850.00, 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500'),
('Socket Set 1/2" Drive', 'Complete socket set with 72 pieces including sockets, ratchets, extensions, and adapters. Chrome vanadium steel construction.', 8500.00, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500'),
('Angle Grinder 4.5"', 'Powerful 850W angle grinder with safety guard and side handle. Includes cutting and grinding discs. Ideal for metal and masonry work.', 6500.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Pliers Set 5-Piece', 'Professional pliers set including slip-joint, needle-nose, diagonal cutting, and locking pliers. Ergonomic handles with non-slip grip.', 3200.00, 'https://images.unsplash.com/photo-1625753783470-1e3e5b6e5b5e?w=500'),
('Level Tool 24"', 'Professional spirit level with 3 vials (horizontal, vertical, 45°). Magnetic base and shock-absorbing end caps. Aluminum construction.', 1800.00, 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500'),
('Power Drill Bits Set', 'Premium 100-piece drill bit set for wood, metal, and masonry. Includes twist bits, spade bits, and masonry bits. Titanium coated for durability.', 4500.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Nail Gun Pneumatic', 'Professional pneumatic nail gun with adjustable depth control. Compatible with 16-gauge nails. Includes air hose and safety glasses.', 22000.00, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500'),
('Safety Helmet with Visor', 'Industrial safety helmet with adjustable visor and chin strap. Meets safety standards. Comfortable padding and ventilation system.', 1200.00, 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500'),
('Work Gloves Heavy Duty', 'Leather work gloves with reinforced palms and fingers. Cut-resistant and heat-resistant. Available in multiple sizes for perfect fit.', 950.00, 'https://images.unsplash.com/photo-1625753783470-1e3e5b6e5b5e?w=500'),
('Toolbox 18"', 'Heavy-duty steel toolbox with removable tray and padlock hasp. Durable construction with comfortable carry handle. Organize all your tools.', 4200.00, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500'),
('Wire Stripper & Cutter', 'Professional wire stripper with built-in cutter and crimper. Handles 10-24 AWG wires. Comfortable grip handles for precision work.', 1500.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Chainsaw 16" Gas Powered', 'Professional gas-powered chainsaw with 2-stroke engine. 16" bar and chain. Safety features include chain brake and throttle lockout.', 35000.00, 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500'),
('Paint Brush Set 12-Piece', 'Professional paint brush set with various sizes and shapes. Synthetic bristles for smooth application. Perfect for interior and exterior painting.', 1800.00, 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500'),
('Extension Cord 15m', 'Heavy-duty extension cord with 3 outlets and on/off switch. Weather-resistant construction. 13A rating for power tools.', 2500.00, 'https://images.unsplash.com/photo-1625753783470-1e3e5b6e5b5e?w=500'),
('Ladder 3-Step Folding', 'Aluminum folding step ladder with non-slip rungs and safety lock. Lightweight yet sturdy. Maximum load capacity 150kg.', 5500.00, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500');

-- Insert 5 Blog Posts with Product Details
INSERT INTO blog (title, content, name, description, price, image_url, author, published_at) VALUES
('Essential Tools for Every Home Workshop', 
'Setting up a home workshop can be overwhelming, but having the right tools makes all the difference. Whether you''re a DIY enthusiast or a professional contractor, certain tools are essential for any project. 

Our Professional Hammer Set is a must-have for any workshop. With heavy-duty construction and fiberglass handles, these hammers provide the perfect balance of power and comfort. The set includes a claw hammer for pulling nails, a ball peen hammer for metalwork, and a rubber mallet for delicate tasks.

When it comes to power tools, nothing beats a reliable cordless drill. Our Cordless Drill Driver Kit offers versatility and convenience with its 18V lithium-ion battery system. The variable speed control allows for precision work, while the LED work light ensures you can see clearly in dimly lit areas.

Remember, investing in quality tools saves you time and money in the long run. Start with these essentials and build your collection as your skills grow.',
'Professional Hammer Set',
'Heavy-duty hammer set with fiberglass handles. Includes claw hammer, ball peen hammer, and rubber mallet. Perfect for construction and DIY projects.',
3500.00,
'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500',
'John Smith',
'2025-01-15 10:30:00'),

('Power Tools: Choosing the Right Circular Saw',
'Circular saws are one of the most versatile power tools in any workshop. They can cut through wood, metal, plastic, and even masonry with the right blade. Choosing the right circular saw depends on your specific needs and projects.

Our Circular Saw 7.25" is designed for both professionals and serious DIYers. With a powerful 15-amp motor delivering 5,800 RPM, it can handle the toughest cutting tasks. The laser guide helps you make precise cuts every time, while the dust blower keeps your cutting line visible.

The saw comes with a high-quality blade and a durable carrying case for easy transport. Whether you''re building a deck, framing a wall, or cutting plywood, this circular saw delivers consistent, clean cuts.

Safety is paramount when using power tools. Always wear safety glasses, use the blade guard, and ensure your workpiece is properly secured before cutting.',
'Circular Saw 7.25"',
'Heavy-duty circular saw with laser guide and dust blower. 15-amp motor delivers 5,800 RPM. Includes blade and carrying case.',
18500.00,
'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500',
'Sarah Johnson',
'2025-01-18 14:20:00'),

('Safety First: Essential Protective Equipment for Construction',
'Working with tools and construction materials requires proper safety equipment. Protecting yourself should always be your top priority on any job site or home project.

Our Safety Helmet with Visor provides comprehensive head protection while maintaining comfort during long work sessions. The adjustable visor protects your eyes from debris, and the ventilation system keeps you cool. The helmet meets all industry safety standards and is essential for any construction or renovation project.

Pair your helmet with our Work Gloves Heavy Duty for complete hand protection. Made from premium leather with reinforced palms, these gloves protect against cuts, abrasions, and heat. The comfortable fit allows for dexterity while maintaining protection.

Remember, safety equipment is not optional—it''s essential. A small investment in proper safety gear can prevent serious injuries and keep you working safely for years to come.',
'Safety Helmet with Visor',
'Industrial safety helmet with adjustable visor and chin strap. Meets safety standards. Comfortable padding and ventilation system.',
1200.00,
'https://images.unsplash.com/photo-1586985289688-ca3cf47d3b6e?w=500',
'Mike Chen',
'2025-01-20 09:15:00'),

('Professional Socket Sets: A Complete Guide',
'A good socket set is the foundation of any mechanic''s or handyman''s toolkit. Socket sets allow you to work on everything from bicycles to automobiles, making them incredibly versatile.

Our Socket Set 1/2" Drive includes 72 pieces covering a wide range of sizes and applications. The chrome vanadium steel construction ensures durability and prevents rust. The set includes various socket sizes, ratchets, extensions, and adapters, giving you the flexibility to tackle any job.

The ratchet mechanism is smooth and reliable, allowing for quick work in tight spaces. The extensions help you reach bolts in difficult locations, while the adapters let you use different drive sizes as needed.

Whether you''re working on your car, motorcycle, or home appliances, a comprehensive socket set like this one will handle virtually any fastening job you encounter.',
'Socket Set 1/2" Drive',
'Complete socket set with 72 pieces including sockets, ratchets, extensions, and adapters. Chrome vanadium steel construction.',
8500.00,
'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500',
'David Williams',
'2025-01-22 16:45:00'),

('Chainsaw Selection: Gas vs Electric for Your Needs',
'Choosing the right chainsaw depends on your specific needs, location, and the type of work you''ll be doing. Gas-powered chainsaws offer more power and mobility, while electric models are quieter and require less maintenance.

Our Chainsaw 16" Gas Powered is perfect for serious cutting tasks. The 2-stroke engine provides reliable power for cutting through thick logs and branches. The 16" bar length is ideal for most tree work and firewood cutting.

Safety features include a chain brake that stops the chain immediately if kickback occurs, and a throttle lockout that prevents accidental acceleration. The ergonomic design reduces fatigue during extended use.

For homeowners with large properties or professionals who need maximum power and portability, a gas-powered chainsaw is the right choice. Always follow safety guidelines, wear protective gear, and maintain your chainsaw regularly for optimal performance.',
'Chainsaw 16" Gas Powered',
'Professional gas-powered chainsaw with 2-stroke engine. 16" bar and chain. Safety features include chain brake and throttle lockout.',
35000.00,
'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500',
'Emma Davis',
'2025-01-25 11:00:00');

