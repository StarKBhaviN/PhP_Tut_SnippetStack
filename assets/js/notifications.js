class NotificationSystem {
    constructor() {
        this.notificationQueue = [];
        this.isShowing = false;
        this.setupStyles();
        this.setupContainer();
    }

    setupStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .notification-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .notification {
                padding: 15px 25px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                display: flex;
                align-items: center;
                gap: 10px;
                animation: slideIn 0.3s ease-out;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .notification.success {
                border-left: 4px solid #4CAF50;
            }

            .notification.error {
                border-left: 4px solid #f44336;
            }

            .notification.warning {
                border-left: 4px solid #ff9800;
            }

            .notification.info {
                border-left: 4px solid #2196F3;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    setupContainer() {
        const container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }

    show(message, type = 'info', duration = 3000) {
        console.log('Showing notification:', { message, type, duration });
        const notification = {
            message,
            type,
            duration
        };

        this.notificationQueue.push(notification);
        this.processQueue();
    }

    processQueue() {
        if (this.isShowing || this.notificationQueue.length === 0) {
            return;
        }

        this.isShowing = true;
        const notification = this.notificationQueue.shift();
        this.displayNotification(notification);
    }

    displayNotification(notification) {
        const container = document.querySelector('.notification-container');
        const element = document.createElement('div');
        element.className = `notification ${notification.type}`;

        const icon = document.createElement('i');
        switch (notification.type) {
            case 'success':
                icon.className = 'fas fa-check-circle';
                break;
            case 'error':
                icon.className = 'fas fa-exclamation-circle';
                break;
            case 'warning':
                icon.className = 'fas fa-exclamation-triangle';
                break;
            default:
                icon.className = 'fas fa-info-circle';
        }

        element.appendChild(icon);
        element.appendChild(document.createTextNode(notification.message));
        container.appendChild(element);

        setTimeout(() => {
            element.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                container.removeChild(element);
                this.isShowing = false;
                this.processQueue();
            }, 300);
        }, notification.duration);
    }
}

// Create global notification instance
const notifications = new NotificationSystem(); 