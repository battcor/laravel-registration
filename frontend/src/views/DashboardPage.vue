<template>
  <div class="dashboard-container">
    <h1>Hello, World</h1>
    <button @click="logout">Logout</button>
  </div>
</template>

<script>
import '../assets/auth.css';

export default {
  name: 'DashboardPage',
  methods: {
    logout() {
      localStorage.removeItem('user_token');
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped></style>