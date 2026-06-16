<?php
include("config.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['login_user'])) {
    echo json_encode(["response" => "Please login to chat with me."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = strtolower(trim($input['message'] ?? ''));

if (empty($message)) {
    echo json_encode(["response" => "I'm ready to assist you. What would you like to know?"]);
    exit;
}

$response = "";

// --- SMART RECOGNITION SYSTEM ---

// 1. Total Criminals / Cases
if (preg_match('/(total|how many|count|kitla|list).*(criminal|case|record|gunegar)/', $message)) {
    $res = mysqli_query($db, "SELECT COUNT(*) as count FROM info");
    $row = mysqli_fetch_assoc($res);
    $response = "Our system currently contains **" . $row['count'] . "** registered criminal records. You can view them all in the Search page.";
} 

// 2. Total Officers
else if (preg_match('/(total|how many|count|kitla|list).*(officer|police|staff)/', $message)) {
    $res = mysqli_query($db, "SELECT COUNT(*) as count FROM registration");
    $row = mysqli_fetch_assoc($res);
    $response = "There are currently **" . $row['count'] . "** active officers registered in the system.";
}

// 3. Crime Type Specific (Murder, Robbery, etc.)
else if (preg_match('/(murder|robbery|kidnapping|rape|fraud|ragging)/', $message, $matches)) {
    $type = ucfirst($matches[1]);
    $res = mysqli_query($db, "SELECT COUNT(*) as count FROM info WHERE crime = '$type'");
    $row = mysqli_fetch_assoc($res);
    $response = "I found **" . $row['count'] . "** cases related to **" . $type . "** in our records.";
}

// 4. Most Recent Case
else if (preg_match('/(recent|last|new|latest).*(crime|case|entry)/', $message)) {
    $res = mysqli_query($db, "SELECT * FROM info ORDER BY id DESC LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $response = "The most recent entry is Case ID **#" . $row['id'] . "** involving **" . $row['name'] . "** for the crime of " . $row['crime'] . ".";
    } else {
        $response = "No crime records found.";
    }
}

// 5. Officer Lookup
else if (preg_match('/(who is|about|officer named|info on).*(officer)/', $message)) {
    $response = "You can view the full list of officers and their contact details in the 'List of Officers' section in the navigation menu.";
}

// 6. Greetings
else if (preg_match('/(hi|hello|hey|good morning|namaste)/', $message)) {
    $response = "Greetings! I am the Neural Assistant for the Criminal Management System. I can provide real-time stats and navigation help. Try asking: 'Total criminals?'";
}

// 7. System Purpose / Help
else if (preg_match('/(help|who are you|what can you do)/', $message)) {
    $response = "I am an AI tool integrated to help you manage and query the criminal database. You can ask me about:\n- Total count of criminals\n- Officer counts\n- Specific crime statistics (e.g. 'How many murder cases?')\n- Recent activity logs";
}

// 8. Search Simulation
else if (preg_match('/search for (.*)/', $message, $match)) {
    $name = mysqli_real_escape_with_mysqli_hack($db, $match[1]); // using basic sanitization logic
    $res = mysqli_query($db, "SELECT * FROM info WHERE name LIKE '%$name%' LIMIT 1");
    if($row = mysqli_fetch_assoc($res)) {
        $response = "Search Result: Found **" . $row['name'] . "** (ID: " . $row['id'] . ") charged with " . $row['crime'] . ".";
    } else {
        $response = "I couldn't find any criminal named '" . $name . "' in the database.";
    }
}

// DEFAULT - ChatGPT style helpful fallback
else {
    $response = "I understand you're interested in '" . $message . "'. While I am specifically tuned for crime records, I can tell you that we currently have active records and officer logs ready. Try asking about 'total criminals' or 'recent cases' for exact data.";
}

echo json_encode(["response" => $response]);

// Small helper for search
function mysqli_real_escape_with_mysqli_hack($db, $val) {
    return mysqli_real_escape_string($db, $val);
}
?>
