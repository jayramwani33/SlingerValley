<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discordWebhookURL = "https://discord.com/api/webhooks/1139189560220332042/6ma5LJtdENFKPEMJOMgc7RlXsqDoNzLUM3gwXY2jP0qZmERjNBgfDWhxnMmoj2p4RrWb"; // Replace with your Discord webhook URL
    
    $discordChannelIDSupport = "1138126730201141248"; // Replace with the ID of your Support channel
    $discordChannelIDReport = "1138126714074042471"; // Replace with the ID of your Report channel
    
    $discordSubject = isset($_POST["subject"]) ? $_POST["subject"] : "";
    $discordDiscordID = isset($_POST["discord"]) ? $_POST["discord"] : "";
    $discordLinks = isset($_POST["links"]) ? $_POST["links"] : "";
    $discordMessage = isset($_POST["msg"]) ? $_POST["msg"] : "";
    
    // Remove potential harmful input
    $discordSubject = htmlspecialchars($discordSubject);
    $discordDiscordID = htmlspecialchars($discordDiscordID);
    $discordLinks = htmlspecialchars($discordLinks);
    $discordMessage = htmlspecialchars($discordMessage);
    
    // Prepare the message based on the selected subject
    if ($discordSubject == "Support") {
        $discordChannelID = $discordChannelIDSupport;
    } elseif ($discordSubject == "Report") {
        $discordChannelID = $discordChannelIDReport;
    } else {
        // Handle other cases if needed
    }
    
    // Compose the message content
    $message = "Subject: $discordSubject\n";
    $message .= "Discord ID: $discordDiscordID\n";
    $message .= "Attachments: $discordLinks\n";
    $message .= "Message: $discordMessage";
    
    // Prepare the data to send to the Discord webhook
    $data = array(
        "content" => $message
    );
    
    $options = array(
        "http" => array(
            "header"  => "Content-type: application/json",
            "method"  => "POST",
            "content" => json_encode($data)
        )
    );
    
    $context  = stream_context_create($options);
    $result = file_get_contents($discordWebhookURL, false, $context);
    
    if ($result === FALSE) {
        // Handle error
        echo "An error occurred while sending the message to Discord.";
    } else {
        // Message sent successfully
        echo "Message sent to Discord.";
    }
}
?>



