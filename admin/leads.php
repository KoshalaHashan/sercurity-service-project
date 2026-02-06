<?php
session_start();
require_once "../backend/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll();

function waLink($mobile){
    $m = preg_replace('/\s+/', '', $mobile);

    // 0771234567 -> 94771234567
    if (preg_match('/^0\d{9}$/', $m)) {
        $m = "94" . substr($m, 1);
    } elseif (preg_match('/^\d{9}$/', $m)) {
        $m = "94" . $m;
    } else {
        $m = ltrim($m, '+');
    }
    return "https://wa.me/" . $m;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Leads | Admin</title>
    <style>
        :root{--navy:#102E50;--gold:#F5C45E;--soft:#f6f8fb;}
        *{box-sizing:border-box;font-family:Segoe UI,sans-serif;}
        body{margin:0;background:var(--soft);color:var(--navy);}
        nav{background:var(--navy);color:#fff;padding:14px 18px;display:flex;gap:14px;align-items:center;}
        nav a{color:#fff;text-decoration:none;font-weight:600;opacity:.9}
        nav a:hover{opacity:1;color:var(--gold)}
        .container{max-width:1200px;margin:0 auto;padding:22px;}
        h1{margin:0 0 16px;}
        .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:12px 14px;border-bottom:1px solid #eee;text-align:left;font-size:14px;}
        th{background:#0c2745;color:#fff;font-weight:700;}
        tr:hover td{background:#fafcff;}
        .badge{padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700;display:inline-block;}
        .pending{background:#fff3cd;color:#7a5b00;}
        .contacted{background:#e9f7ef;color:#0f5132;}
        .btn{border:none;padding:8px 12px;border-radius:10px;font-weight:700;cursor:pointer;}
        .btn-mark{background:var(--gold);color:var(--navy);}
        .btn-mark:hover{filter:brightness(.95);}
        .btn-wa{color:#25D366;font-weight:800;text-decoration:none;}
        .small{opacity:.7;font-size:13px;margin-top:8px}
        @media(max-width:820px){
        th:nth-child(3),td:nth-child(3){display:none;} /* hide interest on small screens */
        }
    </style>
</head>
    <body>
        <nav>
            <strong>Admin</strong>
            <a href="dashboard.php">Dashboard</a>
            <a href="leads.php">Leads</a>
            <a href="joins.php">Join Requests</a>
            <a href="logout.php">Logout</a>
        </nav>

        <div class="container">
        <h1>Leads</h1>
        <div class="card">
            <table>
            <thead>
                <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Interest</th>
                <th>Status</th>
                <th>WhatsApp</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($leads as $l): ?>
                <tr>
                <td><?= htmlspecialchars($l["name"]) ?></td>
                <td><?= htmlspecialchars($l["mobile"]) ?></td>
                <td><?= htmlspecialchars($l["interest"]) ?></td>
                <td>
                    <?php if((int)$l["contacted"] === 1): ?>
                    <span class="badge contacted">✅ Contacted</span>
                    <?php else: ?>
                    <span class="badge pending">❌ Pending</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn-wa" href="<?= waLink($l["mobile"]) ?>" target="_blank">Chat</a>
                </td>
                <td>
                    <?php if((int)$l["contacted"] === 0): ?>
                    <button class="btn btn-mark" onclick="markContacted(<?= (int)$l['id'] ?>,'leads')">
                        Mark Contacted
                    </button>
                    <?php else: ?>
                    —
                    <?php endif; ?>
                </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
        </div>

        <div class="small">Tip: Click “Chat” to open WhatsApp directly and contact the lead instantly.</div>
        </div>

        <script>
            function markContacted(id, table){
            fetch("../backend/mark_contacted.php",{
                method:"POST",
                headers:{"Content-Type":"application/x-www-form-urlencoded"},
                body:`id=${encodeURIComponent(id)}&table=${encodeURIComponent(table)}`
            })
            .then(r=>r.text())
            .then(t=>{
                if(t.trim()==="ok") location.reload();
                else alert("Failed: " + t);
            })
            .catch(()=>alert("Server error"));
            }
        </script>
    </body>
</html>
