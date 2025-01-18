<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>UI Test</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-0 m-auto font-sans antialiased md:p-16 bg-base-100 max-w-7xl">

  <div class="card sm:max-w-sm">
    <figure><img src="https://cdn.flyonui.com/fy-assets/components/card/image-9.png" alt="Watch" /></figure>
    <div class="card-body">
      <h5 class="card-title mb-2.5">Apple Smart Watch</h5>
      <p class="mb-4">Stay connected, motivated, and healthy with the latest Apple Watch.</p>
      <div class="card-actions">
        <button class="btn btn-primary">Buy Now</button>
        <button class="btn btn-secondary btn-soft">Add to cart</button>
      </div>
    </div>
  </div>

  <x-inputz />

</body>
</html>