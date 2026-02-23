<?php
session_start();
require 'db_connection.php'; // Connect to your smart_cultivation DB

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer'){
    header("Location: login.php");
    exit;
}

$crop = $_GET['crop'] ?? "";

// Fetch distinct crops for dynamic buttons
$crops = [];
$cropResult = $conn->query("SELECT DISTINCT crop_name FROM knowledge_base ORDER BY crop_name ASC");
while($row = $cropResult->fetch_assoc()){
    $crops[] = $row['crop_name'];
}

// Fetch knowledge base entries for selected crop
$entries = [];
if($crop != ""){
    $stmt = $conn->prepare("SELECT * FROM knowledge_base WHERE crop_name=? ORDER BY 
        FIELD(section,'Growth Stages','Problems & Solutions','Fertilizer Schedule','Watering Schedule'), id");
    $stmt->bind_param("s", $crop);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $entries[$row['section']][] = $row;
    }
    $stmt->close();
}

// Section Icons
$sectionIcons = [
    "Growth Stages" => "🌱",
    "Problems & Solutions" => "⚠️",
    "Fertilizer Schedule" => "💧",
    "Watering Schedule" => "🚰"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Knowledge Base | Smart Cultivation System</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{--primary-green:#2d8659;--primary-green-dark:#1f5d3f;--primary-green-light:#3da372;--secondary-green:#4caf50;--accent-orange:#ff9800;--accent-yellow:#ffc107;--text-dark:#2c3e50;--text-light:#5a6c7d;--bg-light:#f8f9fa;--bg-white:#ffffff;--border-color:#e0e0e0;--shadow-sm:0 2px 8px rgba(0,0,0,0.08);--shadow-md:0 4px 16px rgba(0,0,0,0.12);--shadow-lg:0 8px 32px rgba(0,0,0,0.16);--transition:all 0.3s cubic-bezier(0.4,0,0.2,1);}
body{font-family:'Inter','Poppins',sans-serif;background:linear-gradient(135deg,#f5f7fa 0%,#e8f5e9 100%);min-height:100vh;color:var(--text-dark);line-height:1.6;padding:20px;}
body::before{content:"";position:fixed;top:0;left:0;width:100%;height:100%;background-image:radial-gradient(circle at 20% 50%,rgba(45,134,89,0.03) 0%,transparent 50%),radial-gradient(circle at 80% 80%,rgba(76,175,80,0.03) 0%,transparent 50%);z-index:0;pointer-events:none;}
.container{position:relative; z-index:1; max-width:1200px; margin:0 auto; animation: fadeInUp 0.6s ease-out;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}
.header{text-align:center;margin-bottom:40px;background:white;padding:40px;border-radius:16px;box-shadow:var(--shadow-sm);animation:slideDown 0.6s ease-out;}
@keyframes slideDown{from{opacity:0;transform:translateY(-30px);}to{opacity:1;transform:translateY(0);}}
.header h1{font-size:32px;font-weight:800;color:var(--primary-green-dark);margin-bottom:8px;display:flex;align-items:center;justify-content:center;gap:12px;}
.header h1 i{color:var(--primary-green);font-size:36px;animation:rotate 3s linear infinite;}
@keyframes rotate{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
.header p{color:var(--text-light);font-size:15px;}
.crop-selection{display:flex;justify-content:center;gap:16px;margin-bottom:16px;flex-wrap:wrap;animation:fadeInUp 0.8s ease-out;animation-delay:0.2s;animation-fill-mode:both;}
.crop-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    color: #ffffff; /* Keep white text */
    text-shadow: 1px 1px 4px rgba(0,0,0,0.3); /* Add shadow for contrast */
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    animation: floatBtn 3s ease-in-out infinite;
    animation-delay: calc(var(--delay,0)*0.1s);
}

/* Optional: make the gradient a bit darker for better contrast */
.crop-btn {
    background: linear-gradient(135deg, #2d8659, #4caf50);
}

@keyframes floatBtn{0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);}}
.crop-btn::before{content:"";position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.3) 0%,transparent 70%);transform:scale(0);transition:transform 0.6s;}
.crop-btn:hover::before{transform:scale(1);}
.crop-btn:hover{transform:translateY(-6px) scale(1.05);box-shadow:var(--shadow-lg);}
.crop-btn.back{background:linear-gradient(135deg,#e74c3c,#c0392b);}
.card{background:white;border-radius:16px;padding:32px;margin-bottom:24px;box-shadow:var(--shadow-sm);border:2px solid transparent;transition:var(--transition);position:relative;overflow:hidden;}
.card::before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(90deg,var(--primary-green),var(--secondary-green));transform:scaleX(0);transition:var(--transition);}
.card:hover{transform:translateY(-4px);box-shadow:var(--shadow-md);border-color:var(--primary-green-light);}
.card:hover::before{transform:scaleX(1);}
.card h2,.card h3{font-weight:800;color:var(--primary-green-dark);margin-bottom:16px;display:flex;align-items:center;gap:12px;font-size:24px;}
.card h3{font-size:20px;margin-bottom:20px;padding-bottom:12px;border-bottom:3px solid var(--bg-light);position:relative;}
.card h3::after{content:"";position:absolute;bottom:-3px;left:0;width:60px;height:3px;background:var(--primary-green);animation:expandWidth 0.6s ease-out;}
@keyframes expandWidth{from{width:0;}to{width:60px;}}
.accordion-item{border:2px solid var(--border-color);border-radius:12px !important;margin-bottom:12px;overflow:hidden;transition:var(--transition);animation:slideInRight 0.4s ease-out;animation-fill-mode:both;}
@keyframes slideInRight{from{opacity:0;transform:translateX(-30px);}to{opacity:1;transform:translateX(0);}}
.accordion-item:hover{border-color:var(--primary-green);box-shadow:var(--shadow-sm);transform:translateX(4px);}
.accordion-button{background:var(--bg-light)!important;color:var(--text-dark)!important;font-weight:600;font-size:16px;padding:18px 20px;border:none;transition:var(--transition);}
.accordion-button:not(.collapsed){background:linear-gradient(135deg,rgba(45,134,89,0.1),rgba(76,175,80,0.1))!important;color:var(--primary-green-dark)!important;}
.accordion-button:focus{box-shadow:0 0 0 3px rgba(45,134,89,0.1);border-color:var(--primary-green);}
.accordion-button::after{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232d8659'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");transition:transform 0.3s ease;}
.accordion-button:not(.collapsed)::after{transform:rotate(180deg);}
.accordion-body{background:white;padding:24px;line-height:1.8;color:var(--text-dark);}
.accordion-body ul{list-style:none;padding-left:0;}
.accordion-body ul li{padding:10px 0 10px 28px;position:relative;color:var(--text-dark);}
.accordion-body ul li::before{content:"✓";position:absolute;left:0;color:var(--primary-green);font-weight:700;font-size:18px;}
.empty-state{text-align:center;padding:60px 20px;background:white;border-radius:16px;box-shadow:var(--shadow-sm);}
.empty-state i{font-size:64px;color:var(--text-light);margin-bottom:20px;opacity:0.5;}
.empty-state h3{font-size:24px;font-weight:700;color:var(--text-dark);margin-bottom:12px;}
.empty-state p{color:var(--text-light);font-size:16px;}
.search-bar{display:flex;justify-content:center;margin-bottom:32px;}
.search-bar input{width:100%;max-width:400px;padding:12px 16px;border-radius:16px;border:2px solid var(--border-color);outline:none;font-size:16px;transition:var(--transition);}
.search-bar input:focus{border-color:var(--primary-green-light);box-shadow:0 2px 8px rgba(45,134,89,0.2);}
mark{background:var(--accent-yellow);color:var(--text-dark);}
</style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1><i class="fas fa-book-open"></i> Knowledge Base</h1>
        <p>Comprehensive guides and expert advice for your crops</p>
    </div>

    <!-- Crop Buttons -->
    <div class="crop-selection">
        <?php foreach($crops as $index => $c): ?>
            <a href="knowledge_base.php?crop=<?php echo urlencode($c); ?>" class="crop-btn" style="--delay: <?php echo $index; ?>;">
                <span>🌿</span>
                <span><?php echo ucfirst($c); ?> Guide</span>
            </a>
        <?php endforeach; ?>
        <a href="farmer_dashboard.php" class="crop-btn back" style="--delay: <?php echo count($crops); ?>;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Search Bar -->
    <?php if($crop!=""): ?>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search within <?php echo ucfirst($crop); ?> guide...">
    </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if($crop==""): ?>
        <div class="card empty-state">
            <i class="fas fa-hand-pointer"></i>
            <h3>Select a Crop Guide</h3>
            <p>Choose a crop from above to view the complete step-by-step cultivation guide</p>
        </div>
    <?php endif; ?>

    <!-- Knowledge Base -->
    <?php if($crop!="" && !empty($entries)): ?>
        <div id="kbContainer">
        <div class="card" style="background:linear-gradient(135deg, rgba(45,134,89,0.1), rgba(76,175,80,0.1)); border:2px solid var(--primary-green-light);">
            <h2 style="margin-bottom:0;"><span class="section-icon">🌿</span> <?php echo ucfirst($crop); ?> — Complete Crop Guide</h2>
            <p style="color:var(--text-light); margin-top:8px; font-size:15px;">Comprehensive cultivation guide with detailed stages, solutions, and schedules</p>
        </div>

        <?php foreach($entries as $section=>$rows):
            $icon = $sectionIcons[$section] ?? "📋";
        ?>
            <div class="card kb-section" data-section="<?php echo strtolower($section); ?>">
                <h3><span class="section-icon"><?php echo $icon; ?></span> <?php echo $section; ?></h3>
                <?php if($section=="Growth Stages"): ?>
                    <div class="accordion" id="<?php echo strtolower(str_replace(' ','_',$section)); ?>">
                        <?php $id=1; foreach($rows as $row): ?>
                        <div class="accordion-item kb-item" data-title="<?php echo strtolower($row['title']); ?>" data-desc="<?php echo strtolower($row['description']); ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $id>1?'collapsed':''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#s<?php echo $id; ?>" aria-expanded="<?php echo $id==1?'true':'false'; ?>">
                                    <i class="fas fa-seedling" style="margin-right:10px; color:var(--primary-green);"></i>
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </button>
                            </h2>
                            <div id="s<?php echo $id; ?>" class="accordion-collapse collapse <?php echo $id==1?'show':''; ?>" data-bs-parent="#<?php echo strtolower(str_replace(' ','_',$section)); ?>">
                                <div class="accordion-body">
                                    <ul>
                                        <?php foreach(array_filter(array_map('trim', explode('.', $row['description']))) as $point):
                                            if(!empty($point)): ?>
                                            <li><?php echo htmlspecialchars($point); ?></li>
                                        <?php endif; endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php $id++; endforeach; ?>
                    </div>
                <?php else: ?>
                    <ul>
                        <?php foreach($rows as $row): ?>
                        <li class="kb-item" data-title="<?php echo strtolower($row['title']); ?>" data-desc="<?php echo strtolower($row['description']); ?>">
                            <b><?php echo htmlspecialchars($row['title']); ?></b>
                            <ul>
                                <?php foreach(array_filter(array_map('trim', explode('.', $row['description']))) as $point):
                                    if(!empty($point)): ?>
                                    <li><?php echo htmlspecialchars($point); ?></li>
                                <?php endif; endforeach; ?>
                            </ul>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    <?php elseif($crop!="" && empty($entries)): ?>
        <div class="card empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Guide Available</h3>
            <p>We're working on adding guides for this crop. Please check back soon!</p>
        </div>
    <?php endif; ?>

</div>

<script>
// Live search with multi-word highlight
const searchInput = document.getElementById('searchInput');
searchInput?.addEventListener('input', function(){
    const query = this.value.toLowerCase().trim();
    const items = document.querySelectorAll('.kb-item');

    if(query===""){
        items.forEach(item=>{
            item.style.display="";
            // Remove previous highlights
            item.querySelectorAll('b, li').forEach(el=>{
                el.innerHTML = el.textContent;
            });
        });
        return;
    }

    const terms = query.split(/\s+/); // Split by spaces

    items.forEach(item=>{
        const title = item.getAttribute('data-title');
        const desc = item.getAttribute('data-desc');
        if(terms.some(term => title.includes(term) || desc.includes(term))){
            item.style.display='';

            // Highlight terms
            item.querySelectorAll('b, li').forEach(el=>{
                let html = el.textContent;
                terms.forEach(term=>{
                    if(term.length>0){
                        const regex = new RegExp(`(${term})`, 'gi');
                        html = html.replace(regex,'<mark>$1</mark>');
                    }
                });
                el.innerHTML = html;
            });

        } else {
            item.style.display='none';
        }
    });
});
</script>

</body>
</html>
