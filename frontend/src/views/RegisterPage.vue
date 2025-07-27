<template>
  <div class="register-container">
    <h1>Register</h1>
    <!-- Step 1: Personal Information -->
    <div v-if="currentStep === 1">
      <form @submit.prevent="nextStep">
        <div class="form-group">
          <label for="firstName">First Name:</label>
          <input type="text" id="firstName" v-model="formData.firstName" required>
          <p v-if="errors.firstName" class="error-message">{{ errors.firstName }}</p>
        </div>
        <div class="form-group">
          <label for="middleName">Middle Name (Optional):</label>
          <input type="text" id="middleName" v-model="formData.middleName">
        </div>
        <div class="form-group">
          <label for="surname">Surname:</label>
          <input type="text" id="surname" v-model="formData.surname" required>
          <p v-if="errors.surname" class="error-message">{{ errors.surname }}</p>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="formData.email" required @blur="handleEmailBlur">
          <p v-if="isEmailChecking" class="email-status-message">Checking availability...</p>
          <p v-else-if="errors.email" class="error-message">{{ errors.email }}</p>
        </div>
        <div class="form-group">
          <label for="mobileNumber">Mobile Number:</label>
          <input type="tel" id="mobileNumber" v-model="formData.mobileNumber" required>
          <p v-if="errors.mobileNumber" class="error-message">{{ errors.mobileNumber }}</p>
        </div>
        <button type="submit" :disabled="isLoading || isEmailChecking">Next</button>
      </form>
    </div>

    <!-- Step 2: Account Information -->
    <div v-else-if="currentStep === 2">
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" v-model="formData.username" required>
          <p v-if="errors.username" class="error-message">{{ errors.username }}</p>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="formData.password" required>
          <p v-if="errors.password" class="error-message">{{ errors.password }}</p>
        </div>
        <div class="form-group">
          <label for="passwordConfirmation">Confirm Password:</label>
          <input type="password" id="passwordConfirmation" v-model="formData.passwordConfirmation" required>
          <p v-if="errors.passwordConfirmation" class="error-message">{{ errors.passwordConfirmation }}</p>
        </div>
        <div class="button-group">
          <button type="button" @click="prevStep" class="back-button" :disabled="isLoading">Back</button>
          <button type="submit" :disabled="isLoading">
            <span v-if="isLoading">Registering...</span>
            <span v-else>Register</span>
          </button>
        </div>
      </form>
    </div>

    <!-- General API messages -->
    <p v-if="apiMessage" :class="{ 'success-message': isSuccess, 'error-message': !isSuccess }" class="api-message">
      {{ apiMessage }}
    </p>

    <p class="auth-navigation">
      Already have an account? <router-link to="/login" class="auth-link">Login here</router-link>
    </p>

    <!-- Success Modal/Popup -->
    <div v-if="showSuccessModal" class="modal-overlay">
      <div class="modal-content">
        <h2>Successfully Signed Up!</h2>
        <p>Your account has been created. You can now log in.</p>
        <button @click="closeSuccessModalAndRedirect">Go to Login</button>
      </div>
    </div>
  </div>
</template>

<script>
import '../assets/auth.css';

export default {
  name: 'RegisterPage',
  data() {
    return {
      currentStep: 1,
      formData: {
        firstName: '',
        middleName: '',
        surname: '',
        email: '',
        mobileNumber: '',
        username: '',
        password: '',
        passwordConfirmation: '',
      },
      errors: {
        firstName: '',
        surname: '',
        email: '',
        mobileNumber: '',
        username: '',
        password: '',
        passwordConfirmation: '',
        api: '',
      },
      isLoading: false,
      isEmailChecking: false,
      apiMessage: '',
      isSuccess: false,
      showSuccessModal: false,
      emailCheckTimeout: null,
    };
  },
  watch: {
    'formData.email'(newEmail, oldEmail) {
      if (newEmail !== oldEmail && this.isValidEmail(newEmail)) {
        this.errors.email = '';
        clearTimeout(this.emailCheckTimeout);
        this.emailCheckTimeout = setTimeout(() => {
          this.checkEmailAvailability();
        }, 500);
      } else if (!newEmail.trim()) {
        this.errors.email = 'Email is required.';
      } else {
        this.errors.email = 'Please enter a valid email address.';
      }
    }
  },
  methods: {
    clearErrors() {
      for (const key in this.errors) {
        this.errors[key] = '';
      }
      this.apiMessage = '';
      this.isSuccess = false;
    },

    validateStep1() {
      this.clearErrors();
      let isValid = true;

      if (!this.formData.firstName.trim()) {
        this.errors.firstName = 'First Name is required.';
        isValid = false;
      }
      if (!this.formData.surname.trim()) {
        this.errors.surname = 'Surname is required.';
        isValid = false;
      }
      if (!this.formData.email.trim()) {
        this.errors.email = 'Email is required.';
        isValid = false;
      } else if (!this.isValidEmail(this.formData.email)) {
        this.errors.email = 'Please enter a valid email address.';
        isValid = false;
      }

      if (!this.formData.mobileNumber.trim()) {
        this.errors.mobileNumber = 'Mobile Number is required.';
        isValid = false;
      } else if (!/^\d+$/.test(this.formData.mobileNumber)) {
        this.errors.mobileNumber = 'Mobile Number must contain only digits.';
        isValid = false;
      }

      return isValid;
    },

    handleEmailBlur() {
      if (this.formData.email.trim() && this.isValidEmail(this.formData.email)) {
        this.checkEmailAvailability();
      } else if (!this.formData.email.trim()) {
        this.errors.email = 'Email is required.';
      } else {
        this.errors.email = 'Please enter a valid email address.';
      }
    },

    async checkEmailAvailability() {
      if (!this.formData.email || !this.isValidEmail(this.formData.email)) {
        return;
      }

      this.isEmailChecking = true;
      this.errors.email = '';

      try {
        const response = await fetch(`https://localhost:8000/api/check-email?email=${encodeURIComponent(this.formData.email)}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
          },
        });

        const data = await response.json();

        if (response.ok) {
          if (!data.available) {
            this.errors.email = data.message || 'This email is already taken.';
          } else {
            this.errors.email = '';
          }
        } else if (response.status === 409) {
          this.errors.email = data.message || 'This email is already taken.';
        } else if (response.status === 422) {
          this.errors.email = data.message || 'Invalid email format provided to server.';
        } else {
          this.errors.email = data.message || 'An error occurred while checking email availability.';
        }
      } catch (error) {
        console.error('Network error during email check:', error);
        this.errors.email = 'Could not connect to server to check email availability.';
      } finally {
        this.isEmailChecking = false;
      }
    },

    validateStep2() {
      this.clearErrors();
      let isValid = true;

      if (!this.formData.username.trim()) {
        this.errors.username = 'Username is required.';
        isValid = false;
      }
      if (!this.formData.password) {
        this.errors.password = 'Password is required.';
        isValid = false;
      } else if (this.formData.password.length < 8) {
        this.errors.password = 'Password must be at least 8 characters long.';
        isValid = false;
      } else if (!/\d/.test(this.formData.password)) {
        this.errors.password = 'Password must contain at least one number.';
        isValid = false;
      }

      if (!this.formData.passwordConfirmation) {
        this.errors.passwordConfirmation = 'Password confirmation is required.';
        isValid = false;
      } else if (this.formData.password !== this.formData.passwordConfirmation) {
        this.errors.passwordConfirmation = 'Passwords do not match.';
        isValid = false;
      }

      return isValid;
    },

    async nextStep() {

      const isStep1ValidSync = this.validateStep1();


      if (!isStep1ValidSync) {
        return;
      }


      if (this.formData.email.trim() && this.isValidEmail(this.formData.email)) {

        if (this.isEmailChecking) {

          this.apiMessage = 'Please wait for email availability check to complete.';
          this.isSuccess = false;
          return;
        }
        if (this.errors.email) {
          return;
        }
      }


      this.currentStep = 2;
    },

    prevStep() {
      this.currentStep = 1;
      this.clearErrors();
    },

    async handleRegister() {
      if (!this.validateStep2()) {
        return;
      }

      this.isLoading = true;
      this.clearErrors();
      this.apiMessage = '';

      try {

        const dataToSend = this.transformToSnakeCase(this.formData);

        const response = await fetch('https://localhost:8000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify(dataToSend),
        });

        const data = await response.json();

        if (response.ok) {
          this.isSuccess = true;
          this.apiMessage = data.message || 'Registration successful!';
          console.log('Registration successful:', data);
          this.showSuccessModal = true;

        } else if (response.status === 422) {
          this.apiMessage = data.message || 'Validation failed. Please check your inputs.';
          this.isSuccess = false;

          if (data.errors) {
            for (const field in data.errors) {


              const mappedField = this.mapBackendFieldToFrontend(field);
              if (this.errors[mappedField] !== undefined) {
                this.errors[mappedField] = data.errors[field][0];
              } else {


                this.errors.api += `${field}: ${data.errors[field][0]} `;
              }
            }
          }
        } else {
          this.apiMessage = data.message || 'An unexpected error occurred. Please try again.';
          this.isSuccess = false;
          this.errors.api = this.apiMessage;
        }
      } catch (error) {
        console.error('Network or API error:', error);
        this.apiMessage = 'Could not connect to the server. Please check your network.';
        this.isSuccess = false;
        this.errors.api = this.apiMessage;
      } finally {
        this.isLoading = false;
      }
    },

    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    mapBackendFieldToFrontend(backendField) {
      const mapping = {
        first_name: 'firstName',
        middle_name: 'middleName',
        last_name: 'surname',
        mobile_number: 'mobileNumber',
        password_confirmation: 'passwordConfirmation',

      };
      return mapping[backendField] || backendField;
    },

    transformToSnakeCase(obj) {
      const newObj = {};
      for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
          const snakeCaseKey = key.replace(/([A-Z])/g, '_$1').toLowerCase();
          newObj[snakeCaseKey] = obj[key];
        }
      }
      return newObj;
    },

    closeSuccessModalAndRedirect() {
      this.showSuccessModal = false;
      this.$router.push('/login');
    }
  },
};
</script>

<style scoped></style>
