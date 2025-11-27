<?php 
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StegTechHub Tooling</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">StegTechHub TOOLING</h1>
            <nav>
                <span class="text-white hover:text-gray-200 mr-4"><?php echo e($_SESSION['user']['username']); ?> (<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</span>
                <a href="index.php?logout='1'" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?></p>
            </div>
        <?php endif ?>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="">
                <strong class="text-gray-700">Welcome, <?php echo $_SESSION['user']['username']; ?></strong>
                <p class="text-sm text-gray-600">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</p>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-2">StegTechHub TOOLING WEBSITE</h2>
        <p class="text-xl mb-8">StegHub.com</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="box bg-white p-4 rounded-lg">
                <a href="https://jenkins.infra.steghub.com/" target="_blank">
                    <img src="img/jenkins.png" alt="Jenkins" class="w-full h-auto transition transform duration-300 hover:scale-105">
                </a>
            </div>
            <div class="box bg-white p-4 rounded-lg">
                <a href="https://grafana.infra.steghub.com/" target="_blank">
                    <img src="img/grafana.png" alt="Grafana" class="w-full h-auto transition transform duration-300 hover:scale-105">
                </a>
            </div>
            </main>

    <footer class="bg-gray-800 text-white text-center p-4 mt-20">
        <p>&copy; 2024 StegTechHub. All rights reserved.</p>
    </footer>
</body>
</html>