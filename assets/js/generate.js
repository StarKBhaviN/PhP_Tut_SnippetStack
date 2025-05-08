// Initialize CodeMirror editor
const codeEditor = CodeMirror.fromTextArea(document.getElementById('code-input'), {
    mode: 'javascript',
    theme: 'monokai',
    lineNumbers: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    matchBrackets: true,
    indentUnit: 4,
    lineWrapping: true
});

// Get DOM elements
const languageSelect = document.getElementById('language');
const editorSelect = document.getElementById('editor');
const snippetNameInput = document.getElementById('snippet-name');
const snippetPrefixInput = document.getElementById('snippet-prefix');
const generateBtn = document.getElementById('generate-btn');
const copyBtn = document.getElementById('copy-btn');
const saveBtn = document.getElementById('save-btn');
const snippetOutput = document.getElementById('snippet-output');

// Attach real-time update listeners (added)
snippetNameInput.addEventListener('input', updateSnippetPreview);
snippetPrefixInput.addEventListener('input', updateSnippetPreview);
languageSelect.addEventListener('change', updateSnippetPreview);
editorSelect.addEventListener('change', updateSnippetPreview);

// Language mode mapping
const languageModes = {
    javascript: 'javascript',
    php: 'php',
    python: 'python',
    html: 'xml',
    css: 'css',
    java: 'text/x-java',
    csharp: 'text/x-csharp'
};

// Language scope mapping with multiple languages
const languageScopes = {
    javascript: 'javascript,typescript,js,jsx,ts,tsx',
    php: 'php,php5,php7',
    python: 'python,py',
    html: 'html,htm,xhtml',
    css: 'css,scss,less,sass',
    java: 'java,jsp',
    csharp: 'csharp,cs'
};

// Update editor mode when language changes
languageSelect.addEventListener('change', () => {
    const mode = languageModes[languageSelect.value];
    codeEditor.setOption('mode', mode);
    updateSnippetPreview();
});

// Generate snippet
generateBtn.addEventListener('click', () => {
    const code = codeEditor.getValue();
    const language = languageSelect.value;
    const editor = editorSelect.value;
    const name = snippetNameInput.value || 'Untitled Snippet';
    const prefix = snippetPrefixInput.value || 'snippet';
    const scope = languageScopes[language];

    let snippet = '';
    
    if (editor === 'vscode') {
        snippet = generateVSCodeSnippet(code, name, prefix, scope);
    } else if (editor === 'sublime') {
        snippet = generateSublimeSnippet(code, name, prefix, scope);
    } else if (editor === 'atom') {
        snippet = generateAtomSnippet(code, name, prefix, scope);
    }

    snippetOutput.textContent = snippet;
});

// Initialize snippet preview with template
function initializeSnippetPreview() {
    const language = languageSelect.value;
    const scope = languageScopes[language];
    const template = `{
    "Snippet Name": {
        "prefix": "trigger",
        "body": [
            "// Your code here"
        ],
        "scope": "${scope}"
    }
}`;
    snippetOutput.textContent = template;
}

// Update snippet preview in real-time
function updateSnippetPreview() {
    const code = codeEditor.getValue();
    const language = languageSelect.value;
    const editor = editorSelect.value;
    const name = snippetNameInput.value || 'Untitled Snippet';
    const prefix = snippetPrefixInput.value || 'snippet';
    const scope = languageScopes[language];

    let snippet = '';
    
    if (editor === 'vscode') {
        snippet = generateVSCodeSnippet(code, name, prefix, scope);
    } else if (editor === 'sublime') {
        snippet = generateSublimeSnippet(code, name, prefix, scope);
    } else if (editor === 'atom') {
        snippet = generateAtomSnippet(code, name, prefix, scope);
    }

    snippetOutput.textContent = snippet;
}

// Add real-time update listener for code editor
codeEditor.on('change', updateSnippetPreview);

// Copy snippet to clipboard
copyBtn.addEventListener('click', () => {
    const snippet = snippetOutput.textContent;
    navigator.clipboard.writeText(snippet).then(() => {
        alert('Snippet copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy snippet: ', err);
        notifications.show('Failed to copy snippet', 'error');
    });
});

// Save snippet to library
if (saveBtn) {
    saveBtn.addEventListener('click', () => {
        const snippet = snippetOutput.textContent;
        const name = snippetNameInput.value;
        const language = languageSelect.value;
        const prefix = snippetPrefixInput.value;

        // Validate inputs
        if (!name || !language || !snippet) {
            notifications.show('Please fill in all required fields', 'error');
            return;
        }

        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        // Send data to server
        fetch('save_snippet.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: name,
                language: language,
                prefix: prefix,
                snippet: snippet
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                notifications.show('Snippet saved successfully!', 'success');
                // Clear form
                snippetNameInput.value = '';
                snippetPrefixInput.value = '';
                codeEditor.setValue('');
                initializeSnippetPreview();
            } else {
                notifications.show(data.message || 'Failed to save snippet', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notifications.show('An error occurred while saving the snippet', 'error');
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Save to Library';
        });
    });
}

// Initialize snippet preview on page load
initializeSnippetPreview();

// Add notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Add notification to the page
    document.body.appendChild(notification);

    // Add glassmorphism effect
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 2rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: ${type === 'error' ? '#ff4444' : '#00C851'};
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    `;

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add keyframe animations
const style = document.createElement('style');
style.textContent = `
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

// Helper functions for generating different editor snippets
function generateVSCodeSnippet(code, name, prefix, scope) {
    const processedCode = processCodeForTabStops(code);
    
    return `{
    "${name}": {
        "prefix": "${prefix}",
        "body": [
${processedCode}
        ],
        "scope": "${scope}"
    }
}`;
}

function generateSublimeSnippet(code, name, prefix, scope) {
    const processedCode = processCodeForTabStops(code);
    
    return `<snippet>
    <content><![CDATA[
${processedCode}
]]></content>
    <tabTrigger>${prefix}</tabTrigger>
    <scope>${scope}</scope>
</snippet>`;
}

function generateAtomSnippet(code, name, prefix, scope) {
    const processedCode = processCodeForTabStops(code);
    
    return `'${name}':
    'prefix': '${prefix}'
    'body': """
${processedCode}
    """
    'scope': '${scope}'`;
}
// added
function updateSnippetPreview() {
    const code = codeEditor.getValue();
    const language = languageSelect.value;
    const editor = editorSelect.value;
    const name = snippetNameInput.value || 'Untitled Snippet';
    const prefix = snippetPrefixInput.value || 'snippet';
    const scope = languageScopes[language];

    let snippet = '';

    if (editor === 'vscode') {
        snippet = generateVSCodeSnippet(code, name, prefix, scope);
    } else if (editor === 'sublime') {
        snippet = generateSublimeSnippet(code, name, prefix, scope);
    } else if (editor === 'atom') {
        snippet = generateAtomSnippet(code, name, prefix, scope);
    }

    snippetOutput.textContent = snippet;
}

// Process code to identify and replace tab stops
function processCodeForTabStops(code) {
    let tabStopCount = 1;
    let lines = code.split('\n');
    let processedLines = [];
    
    // Process each line to identify potential tab stops
    lines.forEach((line, index) => {
        // Replace common patterns with tab stops
        let processedLine = line;
        
        // Handle function parameters
        processedLine = processedLine.replace(/\(([^)]+)\)/g, (match, params) => {
            return `(${params.split(',').map((param, i) => `\${${tabStopCount++}:${param.trim()}}`).join(', ')})`;
        });
        
        // Handle variable declarations
        processedLine = processedLine.replace(/(let|const|var)\s+(\w+)\s*=\s*(.+);?/g, (match, decl, name, value) => {
            return `${decl} \${${tabStopCount++}:${name}} = \${${tabStopCount++}:${value}};`;
        });
        
        // Handle string literals
        processedLine = processedLine.replace(/'([^']+)'|"([^"]+)"/g, (match, single, double) => {
            return `'\${${tabStopCount++}:${single || double}}'`;
        });
        
        // Handle numbers
        processedLine = processedLine.replace(/\b\d+\b/g, (match) => {
            return `\${${tabStopCount++}:${match}}`;
        });
        
        // Add quotes and proper indentation for JSON
        processedLine = `            "${processedLine.replace(/"/g, '\\"')}"`;
        
        processedLines.push(processedLine);
    });
    
    return processedLines.join(',\n');
}

// Add keyboard shortcuts for cursor placement
document.addEventListener('keydown', (e) => {
    if (e.shiftKey && e.key === 'Tab') {
        e.preventDefault();
        const editor = codeEditor;
        const cursor = editor.getCursor();
        const line = editor.getLine(cursor.line);
        
        // Insert tab stop at cursor position
        const beforeCursor = line.substring(0, cursor.ch);
        const afterCursor = line.substring(cursor.ch);
        const newLine = beforeCursor + '${' + (tabStopCount++) + '}' + afterCursor;
        
        editor.replaceRange(newLine, {line: cursor.line, ch: 0}, {line: cursor.line, ch: line.length});
    }
});

// Remove generate button event listener and update real-time generation
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing snippet generator...');
    
    // Initialize CodeMirror
    const editor = CodeMirror.fromTextArea(document.getElementById('code'), {
        mode: 'javascript',
        theme: 'monokai',
        lineNumbers: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        matchBrackets: true,
        indentUnit: 4,
        lineWrapping: true
    });

    // Update snippet preview in real-time
    editor.on('change', function() {
        console.log('Editor content changed, updating snippet...');
        updateSnippetPreview();
    });

    // Add event listeners for other inputs
    document.getElementById('language').addEventListener('change', updateSnippetPreview);
    document.getElementById('editor').addEventListener('change', updateSnippetPreview);
    document.getElementById('snippet-name').addEventListener('input', updateSnippetPreview);
    document.getElementById('snippet-prefix').addEventListener('input', updateSnippetPreview);

    // Save button functionality with debug logs
    const saveButton = document.getElementById('save-snippet');
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            console.log('Save button clicked');
            
            const snippetData = {
                name: document.getElementById('snippet-name').value,
                prefix: document.getElementById('snippet-prefix').value,
                language: document.getElementById('language').value,
                code: editor.getValue(),
                editor: document.getElementById('editor').value
            };

            console.log('Saving snippet data:', snippetData);

            fetch('save_snippet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(snippetData)
            })
            .then(response => {
                console.log('Server response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Server response data:', data);
                if (data.success) {
                    notifications.show('Snippet saved successfully!', 'success');
                } else {
                    notifications.show(data.message || 'Failed to save snippet', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving snippet:', error);
                notifications.show('An error occurred while saving the snippet', 'error');
            });
        });
    }

    // Initialize snippet preview
    initializeSnippetPreview();
}); 