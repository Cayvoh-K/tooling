<?php 
include('functions.php');

if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="">
                <h1 class="text-2xl font-bold">Admin - Home Page</h1>
            </a>
            <nav>
                <a href="admin_tooling.php?logout='1'" class="text-white hover:text-gray-200 mr-4">Logout</a>
                <a href="create_user.php" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100">+ Add User</a>
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
            <?php
            $tools = [
                ['name' => 'Jenkins', 'url' => 'https://jenkins.infra.steghub.com/', 'image' => "img/jenkins.png"],
                ['name' => 'Kubernetes', 'url' => 'https://k8s-metrics.infra.steghub.com/', 'image' => "img/kubernetes.png"],
                ['name' => 'Grafana', 'url' => 'https://grafana.infra.steghub.com/', 'image' => "img/grafana.png"],
                ['name' => 'Rancher', 'url' => 'https://rancher.infra.steghub.com/', 'image' => "img/rancher.png" ],
                ['name' => 'Prometheus', 'url' => 'https://prometheus.infra.steghub.com/', 'image' => "img/prometheus.png"],
                ['name' => 'Kibana', 'url' => 'https://kibana.infra.steghub.com/', 'image' => "img/kibana.png"],
                ['name' => 'Artifactory', 'url' => 'https://artifactory.infra.steghub.com/', 'image' => "img/jfrog.png"]
            ];

            foreach ($tools as $tool) :
            ?>
            <a href="<?php echo $tool['url']; ?>" target="_blank" class="block bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-6 h-48 flex items-center justify-center transform hover:scale-110 transition duration-300">
                    <img src=<?php echo $tool['image']; ?> alt=<?php echo $tool['name']; ?> class="w-full h-auto mx-auto">
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center p-4 mt-20">
        <p>&copy; 2024 StegTechHub. All rights reserved.</p>
    </footer>
</body>
</html>