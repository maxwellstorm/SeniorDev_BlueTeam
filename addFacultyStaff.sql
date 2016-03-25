INSERT INTO department
VALUES (1, "Information Sciences & Technology", "IST"), (2, "Interactive Games & Media", "IGM"), (3, "Computing Security", "CSec");

ALTER TABLE Employees ADD title varchar(100);
ALTER TABLE Employees ALTER COLUMN roomNumber varchar(25);
ALTER TABLE Employees DROP officeHours;
ALTER TABLE Employees ADD secondaryDepartmentID int;
ALTER TABLE room ALTER COLUMN roomNumber varchar(25);

INSERT INTO room VALUES
("GOL 2315", null, null),
("GOL 2615", null, null),
("GOL 2621", null, null),
("GOL 2571", null, null),
("GOL 2329", null, null),
("GOL 2669", null, null),
("GOL 2619", null, null),
("GOL 2617", null, null),
("GOL 2645", null, null),
("GOL 2323", null, null),
("GOL 2331", null, null),
("GOL 2655", null, null),
("GOL 2659", null, null),
("GOL 2443", null, null),
("GOL 2651", null, null),
("GOL 2285", null, null),
("GOL 2627", null, null),
("GOL 26xx", null, null),
("GOL 2657", null, null),
("GOL 2345", null, null),
("GOL 2319", null, null),
("GOL 2675", null, null),
("GOL 2281", null, null),
("GOL 2113", null, null),
("GOL 2303", null, null),
("GOL 2673", null, null),
("GOL 2519", null, null),
("GOL 2635", null, null),
("GOL 2669", null, null),
("GOL 2109", null, null),
("GOL 2103", null, null),
("GOL 2120", null, null),
("GOL 2126", null, null),
("GOL 2121", null, null),
("GOL 2350", null, null),
("GOL 2351", null, null),
("GOL 2107", null, null),
("GOL 2122", null, null),
("GOL 2349", null, null),
("GOL 2157", null, null),
("GOL 2153", null, null),
("GOL 2535", null, null),
("GOL 2559", null, null),
("GOL 2555", null, null),
("GOL 2517", null, null),
("GOL 2503", null, null),
("GOL 2509", null, null),
("GOL 2515", null, null),
("GOL 2569", null, null),
("GOL 2005", null, null),
("GOL 2525", null, null),
("GOL 2571", null, null),
("GOL 2573", null, null),
("GOL 2551", null, null),
("GOL 2575", null, null),
("GOL 2671", null, null),
("GOL 2139", null, null),
("GOL 2545", null, null),
("GOL 2557", null, null),
("GOL 2551", null, null),
("GOL 2511", null, null),
("GOL 2547", null, null),
("SIH 1670", null, null),
("GOL 2537", null, null),
("GOL 2527", null, null),
("GOL 2521", null, null),
("GOL 2518", null, null),
("GOL 4314", null, null),
("GOL 2161", null, null),
("GOL 2373", null, null),
("GOL 2149", null, null),
("GOL 2141", null, null),
("GOL 2131", null, null),
("GOL 2335", null, null),
("GOL 2373", null, null),
("GOL 2365", null, null),
("GOL 2145", null, null),
("GOL 2269", null, null),
("GOL 2325", null, null),
("GOL 3635", null, null),
("LAC 1074", null, null),
("GOL 1547", null, null),
("GOL 2128", null, null),
("GOL 2625", null, null),
("GOL 2307", null, null),
("GOL 3521", null, null),
("GOL 3559", null, null),
("GOL 2321", null, null),
("GOL 2279", null, null),
("GOL 2273", null, null),
("EAS 1327", null, null),
("GOL 2124", null, null),
("GOL 2361", null, null),
("GOL 2122", null, null),
("GOL 2120", null, null),
("GOL 2126", null, null);



INSERT INTO Employees VALUES
(0, "Garret", "Arcoraci", "gpavks@rit.edu", 1, 1, "(585) 475-7854", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2315", "Lecturer"),

(1, "Daniel", "Ashbrook", "daniel.ashbrook@rit.edu", 1, 1, "(585) 475-4784", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2615", "Assistant Professor"),

(2, "Catherine", "Beaton", "catherine.beaton@rit.edu", 1, 1, "(585) 475-6162", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2621", "Associate Professor"),

(3, "Daniel", "Bogaard", "dsbics@rit.edu", 1, 1, "(585) 475-5231", "Teaching and research interests include Web-based communication, security, access, and application development - specifically employing emerging technologies. I have been part of numerous research grants, including awards by the National Science Foundation (NSF), National Institutes of Health (NIH), NYS Department of Health, and Rochester General Hospital. <br />Curricularly, I oversee all Web related curriculum, the Minor in Web Development for Computing Majors and am very interested in Web Sciences.", "MS in Information Technology from Rochester Institute of Technology / BFA in Photography (with honors) from Indiana University", "2011 Eisenhart Award for Outstanding Teaching / Co-PI on NSF grant award-Speech to Text Systems: Comparative Analysis of Text Generation and Display Methods / Co-PI on RGH grant award-Acute Otitis Media Database Project", 1, "GOL 2571", "Associate Professor - Undergraduate Program Director"),

(4, "Charlie", "Border", "cbbics@rit.edu", 1, 1, "(585) 475-7946", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2329", "Associate Professor"),

(5, "Michael", "Floeser", "Michael.Floeser@rit.edu", 1, 1, "(585) 475-7031", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2669", "Senior Lecturer"),

(6, "Bryan", "French", "bdfvks@rit.edu", 1, 1, "(585) 475-6511", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2619", "Lecturer"),

(7, "Erik", "Golen", "efgics@rit.edu", 1, 1, null, "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2617", "Visiting Assistant Professor"),

(8, "Vicki", "Hanson", "vlh@rit.edu", 1, 1, "(585) 475-5384", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2645", "Distinguished Professor"),

(9, "Bruce", "Hartpence", "Bruce.Hartpence@rit.edu", 1, 1, "(585) 475-7938", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2323", "Associate Professor - Building networks and stuff"),

(10, "Larry", "Hill", "Lawrence.Hill@rit.edu", 1, 1, "(585) 475-7854", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2331", "Associate Professor - MS HIT Program Coordinator"),

(11, "Ed", "Holden", "edward.holden@rit.edu", 1, 1, "(585) 475-5361", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2655", "Associate Professor MS IST Program Coordinator"),

(12, "Matt", "Huenerfauth", "matt.huenerfauth@rit.edu", 1, 1, "(585) 475-2459", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2659", "Associate Professor - MS HCI Program Coordinator"),

(13, "Jeff", "Jockel", "jcjics@rit.edu", 1, 1, null, "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2443", "Visiting Lecturer"),

(14, "Jai", "Kang", "jai.kang@rit.edu", 1, 1, "(585) 475-5362", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2651", "Associate Professor"),

(15, "Dan", "Kennedy", "drkisd@rit.edu", 1, 1, "(585) 475-2811", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2285", "Lecturer"),

(16, "Deborah", "LaBelle", "dmlics@rit.edu", 1, 1, "(585) 475-7854", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2627", "Lecturer - BS WMC Progarm Coordinator"),

(17, "Jeffery", "Lasky", "Jeffrey.Lasky@rit.edu", 1, 1, "(585) 475-2284", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 26xx", "Professor - International Progarms Coordinator"),

(18, "Jim", "Leone", "jalvks@rit.edu", 1, 1, "(585) 475-6451", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2657", "Professor"),

(19, "Peter", "Lutz", "Peter.Lutz@rit.edu", 1, 1, "(585) 475-6162", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2345", "Professor"),

(20, "Sharon", "Mason", "Sharon.Mason@rit.edu", 1, 1, "(585) 475-6989", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2319", "Professor"),

(21, "Michael", "McQuaid", "mickmcquaid@gmail.com", 1, 1, null, "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2675", "Lecturer - BS HCC Program Coordinator"),

(22, "Tae (Tom)", "Oh", "Tom.Oh@rit.edu", 1, 1, "(585) 475-7642", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2281", "Associate Professor - MS NSA Program Coordinator", 3),

(23, "Sylvia", "Perez-Hardy", "sylvia.perez-hardy@rit.edu", 1, 1, "(585) 475-7941", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2113", "Associate Professor - BS CIT Program Coordinator"),

(24, "Nirmala", "Shenoy", "nxsvks@rit.edu", 1, 1, "(585) 475-4887", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2303", "Professor"),

(25, "Brian", "Tomaszewski", "bmtski@rit.edu", 1, 1, "(585) 475-2869", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2673", "Assistant Professor"),

(26, "Ronald", "Vullo", "rpv@mail.rit.edu", 1, 1, "(585) 475-7281", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2519", "Associate Professor - Web Design & Development"),

(27, "Elissa", "Weeden", "emwics@rit.edu", 1, 1, "(585) 475-6733", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2635", "Associate Professor"),

(28, "Qi", "Yu", "qyuvks@rit.edu", 1, 1, "(585) 475-6929", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2669", "Associate Professor - Graduate Program Director"),

(29, "Steve", "Zilora", "sjzics@rit.edu", 1, 1, "(585) 475-7643", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2109", "Associate Professor - Department Chair"),

(30, "Rhonda", "Baker-Smith", "rdbcst@rit.edu", 1, 0, "(585) 475-7924", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2103", "IST Office Manager"),

(31, "Melissa", "Hanna", "mchics@rit.edu", 1, 0, "(585) 475-6179", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2120", "Senior Staff Assistant"),

(32, "Betty", "Hillman", "echics@rit.edu", 1, 0, "(585) 475-2700", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2126", "Academic Advisor", 2),

(33, "Matt", "Lake", "Matt.Lake@rit.edu", 1, 0, "(585) 475-2700", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2121", "Senior Academic Advisor - Information Technology: Honors"),

(34, "Mark", "Lessard", "mark@it.rit.edu", 1, 0, "(585) 475-2321", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2350", "Technician", 2),

(35, "Jimmy", "McNatt", "jdmics@rit.edu", 1, 0, "(585) 475-7931", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2351", "Systems Administrator"),

(36, "Matt", "Noble", "manics@rit.edu", 1, 0, "(585) 475-4302", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2107", "Student Experience Coordinator"),

(37, "Jill", "Persson", "jmpics@rit.edu", 1, 0, "(585) 475-5038", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2121", "Graduate Academic Advisor", 3),

(38, "Theresa", "Pozzi", "theresa@it.rit.edu", 1, 0, "(585) 475-7321", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2122", "Senior Staff Specialist"),

(39, "John", "Simonson", "jssics@rit.edu", 1, 0, null, "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 1, "GOL 2349", "Infrastructure Systems Admin"),

(40, "David", "Schwartz", "disvks@rit.edu", 1, 1, "(585) 475-5521", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2157", "Associate Professor - Director of IGM"),

(41, "Jessica", "Bayliss", "jdbics@rit.edu", 1, 1, "(585) 475-2507", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2153", "Associate Professor - Associate Director"),

(42, "Kevin", "Bierre", "kjbics@rit.edu", 1, 1, "(585) 475-5358", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2535", "Associate Professor"),

(43, "Al", "Biles", "mark@rit.edu", 1, 1, "(585) 475-4149", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2559", "Professor"),

(44, "Alberto", "Bobadilla Sotelo", "labigm@rit.edu", 1, 1, "(585) 475-4391", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2555", "Lecturer"),

(45, "Sean", "Boyle", "seaboy@mail.rit.edu", 1, 1, "(585) 475-4517", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2517", "Lecturer"),

(46, "Christopher", "Cascioli", "cdccis@rit.edu", 1, 1, "(585) 475-2533", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2503", "Lecturer"),

(47, "Erin", "Cascioli", "edcigm@rit.edu", 1, 1, "(585) 475-6593", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2509", "Lecturer"),

(48, "Adrienne", "Decker", "amdigm@rit.edu", 1, 1, "(585) 475-4653", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2515", "Assistant Professor"),

(49, "Nancy", "Doubleday", "nrdics@rit.edu", 1, 1, "(585) 475-7324", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2569", "Associate Professor"),

(50, "Chris", "Egert", "caeics@rit.edu", 1, 1, "(585) 475-4873", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2005", "Associate Professor - Associate Director of the MAGIC Center"),

(51, "Gordon", "Goodman", "gigdfp@ad.rit.edu", 1, 1, "(585) 475-6690", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2525", "Professor"),

(52, "Owen", "Gottlieb", "oagigm@rit.edu", 1, 1, "(585) 475-5364", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2571", "Assistant Professor"),

(53, "W. Michelle", "Harris", "wmhics@rit.edu", 1, 1, "(585) 475-4487", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2573", "Associate Professor"),

(54, "Tona", "Henderson", "tahics@rit.edu", 1, 1, "(585) 475-7243", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2551", "Associate Professor"),

(55, "Jay", "Jackson", "jajvks@rit.edu", 1, 1, "(585) 475-4634", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2575", "Associate Professor"),

(56, "Stephen", "Jacobs", "sxjics@rit.edu", 1, 1, "(585) 475-7803", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2671", "Professor, Associate Director of the MAGIC Center"),

(57, "Anthony", "Jefferson", "acjvks@rit.edu", 1, 1, "(585) 475-5910", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2139", "Senior Lecturer"),

(58, "Elizabeth", "Lawley", "elizabeth.lawley@rit.edu", 1, 1, "(585) 475-6896", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2545", "Professor - Director of the Lab for Social Computing"),

(59, "Steve", "Maier", "swmigm@rit.edu", 1, 1, "(585) 475-5322", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2557", "Lecturer"),

(60, "Sten", "McKinzie", "semigm@rit.edu", 1, 1, "(585) 475-4776", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2551", "Lecturer"),

(61, "Jesse", "O'Brien", "jsoigm@rit.edu", 1, 1, "(585) 475-6251", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2511", "Lecturer"),

(62, "Elouise", "Oyzon", "eroics@rit.edu", 1, 1, "(585) 475-6542", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2547", "Associate Professor"),

(63, "Andrew", "Phelps", "andy@mail.rit.edu", 1, 1, "(585) 475-6758", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "SIH 1670", "Professor - Director of the MAGIC Center"),

(64, "Charlie", "Roberts", "cdrigm@rit.edu", 1, 1, "(585) 475-2537", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2537", "Assistant Professor"),

(65, "Ian", "Schreiber", "imsigm@rit.edu", 1, 1, "(585) 475-4174", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2527", "Assistant Professor"),

(66, "David", "Simkins", "dwsigm@rit.edu", 1, 1, "(585) 475-4991", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2521", "Assistant Professor"),

(67, "Cyprian", "Tayrien", "cbtigm@rit.edu", 1, 1, "(585) 475-5033", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2518", "Lecturer"),

(68, "Cody", "Van De Mark", "cavigm@rit.edu", 1, 1, "(585) 475-4314", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 4314", "Lecturer"),

(69, "Jill", "Bray", "jcbics@rit.edu", 1, 0, "(585) 475-7657", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2161", "Assistant to the Director"),

(70, "Ed", "Huyer", "erhvks@rit.edu", 1, 0, "(585) 475-6651", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2373", "Laboratory Manager and Systems Specialist"),

(72, "Beth", "Livecchi", "bmlpsn@rit.edu", 1, 0, "(585) 475-4965", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2149", "Operations Manager"),

(73, "Amanda", "Scheerbaum", "absrla@rit.edu", 1, 0, "(585) 475-4729", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2141", "Senior Academic Advisor"),

(74, "Kathleen", "Schreier Rudgers", "kmsrla@rit.edu", 1, 0, "(585) 475-6756", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2131", "Senior Academic Advisor"),

(75, "Pamela", "Venuti", "plvvks@rit.edu", 1, 0, "(585) 475-5935", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2335", "Systems Administrator"),

(76, "Ann", "Warren", "ann.warren@rit.edu", 1, 0, "(585) 475-6305", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2373", "Lab Manager & Systems Specialist"),

(77, "Chad", "Weeden", "cewics@rit.edu", 1, 0, "(585) 475-7306", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2365", "Assistant Director"),

(78, "Shameelah", "Wilson", "scwigm@rit.edu", 1, 0, "(585) 475-7453", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 2, "GOL 2145", "Senior Staff Assistant"),

(79, "Jayalaxmi", "Chakravarthy", "jxcics@rit.edu", 1, 1, "(585) 475-5136", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2269", "Lecturer"),

(80, "Daryl", "Johnson", "daryl.johnson@rit.edu", 1, 1, "(585) 475-5072", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2325", "Associate Professor"),

(81, "Alan", "Kaminsky", "ark@cs.rit.edu", 1, 1, "(585) 475-6789", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 3635", "Professor - Extended Faculty"),

(82, "Rui", "Li", "rxlics@rit.edu", 1, 1, null, "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "LAC 1074", "Faculty"),

(83, "Andy", "Meneely", "andy@se.rit.edu", 1, 1, "(585) 475-7829", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 1547", "Assistant Professor - Extended Faculty"),

(84, "Sumita", "Mishra", "sumita.mishra@rit.edu", 1, 1, "(585) 475-4475", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2128", "Associate Professor and Graduate Program Director"),

(85, "Rick", "Mislan", "rpmics@rit.edu", 1, 1, "(585) 475-2801", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2625", "Visiting Assistant Professor"),

(86, "Yin", "Pan", "yin.pan@rit.edu", 1, 1, "(585) 475-4645", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2307", "Associate Professor"),

(87, "Leonid", "Reznik", "lr@cs.rit.edu", 1, 1, "(585) 475-7210", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 3521", "Professor - Extended Faculty"),

(88, "Carol", "Romanowski", "cjrcms@cs.rit.edu", 1, 1, "(585) 475-4912", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 3559", "Associate Professor - Extended Faculty"),

(89, "Chaim", "Sanders", "cesics@rit.edu", 1, 1, "(585) 475-4316", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2321", "Lecturer"),

(90, "Bill", "Stackpole", "bill.stackpole@rit.edu", 1, 1, "(585) 475-5351", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2279", "Associate Professor"),

(91, "Jonathan", "Weissman", "ljswics@rit.edu", 1, 1, "(585) 475-4644", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2273", "Lecturer"),

(92, "Josephine", "Wolff", "jcwgpt@rit.edu", 1, 1, "(585) 475-4434", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "EAS 1327", "Extended Faculty"),

(93, "Bo", "Yuan", "bo.yuan@rit.edu", 1, 1, "(585) 475-4468", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2124", "Associate Professor and Department Chair"),

(94, "David", "Emlen", "dte@it.rit.edu", 1, 0, "(585) 475-5232", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2361", "Systems Administrator"),

(95, "Megan", "Fritts", "mcfics@rit.edu", 1, 0, "(585) 475-2963", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2122", "Academic Advisor"),

(96, "Nicholas", "Litchfield", "nplics@rit.edu", 1, 0, "(585) 475-4053", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2120", "Grant Administrator"),

(97, "Liz", "Zimmerman", "exzics@rit.edu", 1, 0, "(585) 475-6161", "ABOUT GOES HERE", "EDUCATION GOES HERE", "HIGHLIGHTS GO HERE", 3, "GOL 2126", "Office Operations Manager");