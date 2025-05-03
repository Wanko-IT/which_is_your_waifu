<template>
  <div class="app-layout">
    <header class="app-header">
      <div class="container">
        <h1 class="site-title">
          <nuxt-link to="/">Which Is Your Waifu?</nuxt-link>
        </h1>
        <nav class="main-nav">
          <ul>
            <li><nuxt-link to="/">Vote</nuxt-link></li>
            <li><nuxt-link to="/results">Results</nuxt-link></li>
          </ul>
        </nav>
      </div>
    </header>
    
    <main class="app-content">
      <nuxt />
    </main>
    
    <footer class="app-footer">
      <div class="container">
        <p>&copy; {{ new Date().getFullYear() }} Which Is Your Waifu. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-header {
  background-color: #ff6b6b;
  color: white;
  padding: 1rem 0;
}

.app-header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.site-title {
  margin: 0;
  font-size: 1.5rem;
}

.site-title a {
  color: white;
  text-decoration: none;
}

.main-nav ul {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
}

.main-nav li {
  margin-left: 1.5rem;
}

.main-nav a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.3s ease;
}

.main-nav a:hover {
  opacity: 0.8;
}

.app-content {
  flex: 1;
}

.app-footer {
  background-color: #f5f5f5;
  padding: 1.5rem 0;
  text-align: center;
  color: #666;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}
</style>
