<?php
// Public chat bot for home and about pages
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
            <i class="fas fa-robot"></i> LearnWell Assistant
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello! Welcome to LearnWell, your online learning platform. I'm here to help you discover our courses, understand how to get started, and answer any questions about our learning system. How can I assist you today?
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
        
        // General LearnWell responses
        if (message.includes('course') || message.includes('class')) {
            return "LearnWell offers a wide variety of courses across different subjects. You can browse all available courses after creating an account and logging in. What specific subject are you interested in learning?";
        }
        
        if (message.includes('sign up') || message.includes('register') || message.includes('create account')) {
            return "To get started with LearnWell, click the 'Sign Up' button in the navigation menu. You can create an account as either a student or instructor, depending on your role.";
        }
        
        if (message.includes('login') || message.includes('sign in')) {
            return "If you already have an account, click the 'Login' button in the navigation menu to access your dashboard and start learning!";
        }
        
        if (message.includes('student') || message.includes('learner')) {
            return "As a student, you can browse courses, enroll in classes, track your progress, take quizzes, participate in discussions, and earn certificates upon completion. It's a comprehensive learning experience!";
        }
        
        if (message.includes('instructor') || message.includes('teacher')) {
            return "Instructors can create and manage courses, add content and materials, create quizzes, track student progress, and generate certificates. It's a powerful platform for teaching!";
        }
        
        if (message.includes('certificate') || message.includes('certification')) {
            return "Yes! Students can earn certificates upon completing courses and passing assessments. These certificates are great for your portfolio and professional development.";
        }
        
        if (message.includes('about') || message.includes('what is') || message.includes('learnwell')) {
            return "LearnWell is an online learning platform designed to provide a user-friendly and accessible experience for learners, instructors, and administrators. We offer seamless course management, progress tracking, and interactive learning features.";
        }
        
        if (message.includes('help') || message.includes('support')) {
            return "I'm here to help! You can ask me about courses, how to sign up, what features are available, or any general questions about LearnWell. What would you like to know?";
        }
        
        if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
            return "Hello! Welcome to LearnWell. How can I help you discover our learning platform today?";
        }
        
        if (message.includes('thank')) {
            return "You're welcome! Is there anything else you'd like to know about LearnWell?";
        }
        
        if (message.includes('feature') || message.includes('what can')) {
            return "LearnWell offers many features: course browsing and enrollment, progress tracking, interactive quizzes, discussion forums, certificate generation, responsive design, and tools for both students and instructors.";
        }
        
        if (message.includes('free') || message.includes('cost') || message.includes('price')) {
            return "LearnWell is designed to be accessible and user-friendly. For specific pricing information, please check our signup page or contact our support team.";
        }
        
        // Default responses
        const defaultResponses = [
            "That's a great question! LearnWell is designed to provide an excellent learning experience. What specific aspect would you like to know more about?",
            "I'd be happy to help you understand LearnWell better. Are you looking for information about courses, how to get started, or our features?",
            "LearnWell offers a comprehensive learning platform. Would you like to know about course enrollment, instructor features, or how to create an account?",
            "I'm here to help you navigate LearnWell. Are you interested in becoming a student, instructor, or learning more about our platform?",
            "That's an interesting question! Let me know if you need help with account creation, course information, or any other aspect of LearnWell."
        ];
        
        return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
    }
</script> 