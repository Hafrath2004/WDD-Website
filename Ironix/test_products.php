<!DOCTYPE html>
<html>
<head>
    <title>Test Products API</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Products API Test</h1>
    <div id="result">Testing...</div>
    
    <script>
        fetch('get_products.php?all=1')
            .then(res => {
                console.log('Response status:', res.status);
                return res.json();
            })
            .then(data => {
                const resultDiv = document.getElementById('result');
                if (data.error) {
                    resultDiv.innerHTML = '<p class="error">Error: ' + data.error + '</p>';
                } else {
                    resultDiv.innerHTML = '<p class="success">Success! Found ' + data.products.length + ' products</p>';
                    resultDiv.innerHTML += '<h3>Products Data:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                }
            })
            .catch(error => {
                document.getElementById('result').innerHTML = '<p class="error">Fetch Error: ' + error.message + '</p>';
            });
    </script>
</body>
</html>

