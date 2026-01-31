<?php
// Only show chat bot if user is logged in as instructor
if (isset($_SESSION['username']) && isset($_SESSION['instructor_id'])) {
?>
<style>
    /* Chat Bot Styles */
    .chat-bot {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        font-family: Arial, sans-serif;
    }

    .chat-bot-toggle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        color: white;
        font-size: 24px;
    }

    .chat-bot-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }

    .chat-bot-container {
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        display: none;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        text-align: center;
        font-weight: bold;
    }

    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .message {
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
    }

    .message.user {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 18px;
        word-wrap: break-word;
    }

    .message.bot .message-content {
        background: white;
        border: 1px solid #e9ecef;
        margin-right: 10px;
    }

    .message.user .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .chat-input {
        padding: 15px;
        border-top: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        background: white;
    }

    .chat-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
        margin-right: 10px;
    }

    .chat-input button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chat-input button:hover {
        transform: scale(1.05);
    }

    .typing-indicator {
        display: none;
        padding: 10px 15px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 18px;
        margin-bottom: 10px;
        max-width: 80%;
    }

    .typing-dots {
        display: flex;
        align-items: center;
    }

    .typing-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #999;
        margin: 0 2px;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
</style>

<!-- AI Chat Bot -->
<div class="chat-bot">
    <div class="chat-bot-toggle" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </div>
    <div class="chat-bot-container" id="chatContainer">
        <div class="chat-header">
            <i class="fas fa-robot"></i> LearnWell Instructor Assistant
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello <?=$_SESSION['username']?>! I'm your LearnWell instructor assistant. How can I help you with course management today? You can ask me about creating courses, managing content, student progress, or teaching tips!
                </div>
            </div>
        </div>
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Chat Bot JavaScript
    function toggleChat() {
        const container = document.getElementById('chatContainer');
        const toggle = document.querySelector('.chat-bot-toggle');
        
        if (container.style.display === 'flex') {
            container.style.display = 'none';
            toggle.innerHTML = '<i class="fas fa-comments"></i>';
        } else {
            container.style.display = 'flex';
            toggle.innerHTML = '<i class="fas fa-times"></i>';
            document.getElementById('chatInput').focus();
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if (message === '') return;
        
        // Add user message
        addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate AI response
        setTimeout(() => {
            hideTypingIndicator();
            const response = generateAIResponse(message);
            addMessage(response, 'bot');
        }, 1000 + Math.random() * 2000);
    }

    function addMessage(text, sender) {
        const messagesContainer = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.textContent = text;
        
        messageDiv.appendChild(contentDiv);
        messagesContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTypingIndicator() {
        document.getElementById('typingIndicator').style.display = 'block';
        document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
    }

    function hideTypingIndicator() {
        document.getElementById('typingIndicator').style.display = 'none';
    }

    function generateAIResponse(userMessage) {
        const message = userMessage.toLowerCase();
        
        // Instructor-specific responses
        if (message.includes('create course') || message.includes('add course')) {
            return "To create a new course, click the 'Add Course' button in the navigation menu. You'll be able to set the course title, description, and upload a cover image. Make sure to add course content and materials after creation.";
        }
        
        if (message.includes('content') || message.includes('material')) {
            return "You can add course content and materials through the course management interface. Navigate to your course and look for options to add lessons, videos, documents, or other learning materials.";
        }
        
        if (message.includes('student') || message.includes('enrollment')) {
            return "You can view student enrollments and progress in your course management dashboard. Track their quiz scores, completion rates, and engagement with your course materials.";
        }
        
        if (message.includes('quiz') || message.includes('assessment')) {
            return "Create quizzes and assessments to test student knowledge. You can add multiple-choice questions, true/false, or other question types. These help track student progress and understanding.";
        }
        
        if (message.includes('certificate') || message.includes('certification')) {
            return "Students can earn certificates upon completing your courses. You can customize certificate requirements and track which students have earned them through your instructor dashboard.";
        }
        
        if (message.includes('status') || message.includes('public') || message.includes('private')) {
            return "You can control the visibility of your courses by changing their status between Public and Private. Public courses are visible to all students, while Private courses are hidden.";
        }
        
        if (message.includes('edit') || message.includes('update')) {
            return "You can edit your course details, content, and materials at any time. Use the edit options in your course management interface to make updates and improvements.";
        }
        
        if (message.includes('profile') || message.includes('account')) {
            return "Manage your instructor profile and account settings from the navigation menu. Update your information, change passwords, and customize your instructor profile there.";
        }
        
        if (message.includes('help') || message.includes('support')) {
            return "I'm here to help with your course management! You can ask me about creating courses, managing content, tracking students, or any other instructor features.";
        }
        
        if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
            return "Hello! Welcome to your instructor dashboard. How can I assist you with course management today?";
        }
        
        if (message.includes('thank')) {
            return "You're welcome! Is there anything else I can help you with regarding your courses or teaching experience?";
        }
        
        if (message.includes('teaching') || message.includes('tips') || message.includes('best practice')) {
            return "Great question! Here are some teaching tips: Create engaging content, use multimedia, provide clear instructions, give regular feedback, encourage student interaction, and keep your courses updated with current information.";
        }
        
        if (message.includes('analytics') || message.includes('report') || message.includes('statistics')) {
            return "Track your course performance through analytics and reports. Monitor student engagement, completion rates, quiz scores, and other metrics to improve your teaching effectiveness.";
        }
        
        // Default responses
        const defaultResponses = [
            "That's a great question! As an instructor, you can create courses, manage content, track student progress, and generate certificates. What specific aspect would you like to know more about?",
            "I'm here to help you make the most of your teaching experience. Are you looking for information about course creation, student management, or content development?",
            "As a LearnWell instructor, you have powerful tools for course creation and student engagement. What would you like to know more about?",
            "I'd be happy to help you navigate your instructor dashboard. Are you looking for course management help, teaching tips, or student tracking information?",
            "That's an interesting question! Let me know if you need help with course creation, content management, or any other instructor features."
        ];
        
        return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
    }
</script>
<?php
}
?> 