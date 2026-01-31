<?php
// Only show chat bot if user is logged in as student
if (isset($_SESSION['username']) && isset($_SESSION['student_id'])) {
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
            <i class="fas fa-robot"></i> LearnWell Student Assistant
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello <?=$_SESSION['username']?>! I'm your LearnWell student assistant. How can I help you with your learning journey today? You can ask me about courses, enrollment, progress tracking, or study tips!
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
        
        // Student-specific responses
        if (message.includes('enroll') || message.includes('join course')) {
            return "To enroll in a course, click the 'View Course' button on any course card, then look for the enrollment option. You'll be able to access all course materials once enrolled!";
        }
        
        if (message.includes('progress') || message.includes('track')) {
            return "You can track your learning progress in your enrolled courses. Check your dashboard or course pages to see your quiz scores, completion status, and certificates earned.";
        }
        
        if (message.includes('certificate') || message.includes('certification')) {
            return "Yes! You can earn certificates upon completing courses and passing the required assessments. These certificates are great for your portfolio and professional development.";
        }
        
        if (message.includes('quiz') || message.includes('test') || message.includes('assessment')) {
            return "Courses include quizzes and assessments to test your knowledge. Make sure to complete them to track your progress and earn your certificate!";
        }
        
        if (message.includes('discussion') || message.includes('forum')) {
            return "Many courses have discussion forums where you can interact with other students and instructors. It's a great way to ask questions and share insights!";
        }
        
        if (message.includes('profile') || message.includes('account')) {
            return "You can manage your profile and account settings from the navigation menu. Update your information, change passwords, and view your learning history there.";
        }
        
        if (message.includes('course') || message.includes('class')) {
            return "Browse through all available courses on this page. Click 'View Course' to see details and enroll. You can filter and search for specific subjects you're interested in.";
        }
        
        if (message.includes('help') || message.includes('support')) {
            return "I'm here to help with your learning journey! You can ask me about courses, enrollment, progress tracking, study tips, or any other questions about LearnWell.";
        }
        
        if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
            return "Hello! Welcome to your student dashboard. How can I assist you with your learning today?";
        }
        
        if (message.includes('thank')) {
            return "You're welcome! Is there anything else I can help you with regarding your courses or learning experience?";
        }
        
        if (message.includes('study') || message.includes('learn') || message.includes('tips')) {
            return "Great question! Here are some study tips: Set regular study times, take notes during lessons, participate in discussions, complete all quizzes, and don't hesitate to ask questions in the forums.";
        }
        
        // Default responses
        const defaultResponses = [
            "That's a great question! As a student, you have access to course enrollment, progress tracking, and certificates. What specific aspect would you like to know more about?",
            "I'm here to help you make the most of your learning experience. Are you looking for information about courses, enrollment, or your progress?",
            "As a LearnWell student, you can enroll in courses, track your progress, and earn certificates. What would you like to know more about?",
            "I'd be happy to help you navigate your student dashboard. Are you looking for course information, enrollment help, or study tips?",
            "That's an interesting question! Let me know if you need help with course enrollment, progress tracking, or any other student features."
        ];
        
        return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
    }
</script>
<?php
}
?> 