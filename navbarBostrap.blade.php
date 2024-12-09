<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        
        <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
        <x-nav-link href="/hi" :active="request()->is('hi')">hi</x-nav-link>
        <x-nav-link href="/halo" :active="request()->is('halo')">halo</x-nav-link>
        <x-nav-link href="/post" :active="request()->is('post')">Post</x-nav-link>
      
      </ul>
    </div>
  </div>
</nav>