<template>
  <div class="login-container">
    <h1>Login</h1>
    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" v-model="username" required :disabled="isLoading">
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required :disabled="isLoading">
      </div>
      <button type="submit" :disabled="isLoading || isSuccess">
        <span v-if="isLoading">Logging in...</span>
        <span v-else>Login</span>
      </button>
    </form>

    <p v-if="apiMessage" :class="{ 'success-message': isSuccess, 'error-message': !isSuccess }" class="api-message">
      {{ apiMessage }}
    </p>

    <p class="auth-navigation">Don't have an account? <router-link to="/register" class="auth-link">Register
        here</router-link></p>
  </div>
</template>

<script>
import '../assets/auth.css';

export default {
  name: 'LoginPage',
  data() {
    return {
      username: '',
      password: '',
      isLoading: false,
      apiMessage: '',
      isSuccess: false,
    };
  },
  methods: {
    clearMessages() {
      this.apiMessage = '';
      this.isSuccess = false;
    },

    async handleLogin() {
      this.isLoading = true;
      this.clearMessages();

      try {
        const response = await fetch('https://localhost:8000/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            username: this.username,
            password: this.password,
          }),
        });

        const data = await response.json();

        if (response.ok) {
          this.isSuccess = true;
          this.apiMessage = data.message || 'Login successful!';
          console.log('Login successful:', data);
          if (data.access_token) {
            localStorage.setItem('user_token', data.access_token);
          } else {
            localStorage.setItem('user_token', 'dummy_jwt_token_from_login');
          }
          setTimeout(() => {
            this.$router.push('/dashboard');
          }, 1500);

        } else if (response.status === 401) {
          this.apiMessage = data.message || 'Invalid username or password.';
          this.isSuccess = false;
        } else if (response.status === 422) {
          this.apiMessage = data.message || 'Validation failed. Please check your inputs.';
          this.isSuccess = false;

        } else {
          this.apiMessage = data.message || 'An unexpected error occurred. Please try again.';
          this.isSuccess = false;
        }
      } catch (error) {
        console.error('Network or API error:', error);
        this.apiMessage = 'Could not connect to the server. Please check your network.';
        this.isSuccess = false;
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped></style>
