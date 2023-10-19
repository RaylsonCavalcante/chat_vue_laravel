<template>
	<div class="login-spinner"></div>
    <div class="bg-loading-login">
    <section class="vh-100" style="background-color: #81C784;">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <!-- Login -->
            <div class="card shadow-2-strong" v-if="showFormLogin" id="divLogin" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">

                <h3 class="mb-5" style="color: #81C784;"><i class="far fa-comments" style="color: #81C784;"></i> Login</h3>

                <form id="login" @submit.prevent="login">
                <div class="mb-4">
                  <input type="email" v-model="userLogin.email" name="email" required class="form-control form-control-lg" placeholder="Email" />
                </div>

                <div class="mb-4">
                  <input type="password" v-model="userLogin.password" name="password" required id="password" class="form-control form-control-lg" placeholder="Senha" />
                </div>

                <!-- Checkbox -->
                <div class="form-check d-flex justify-content-start mb-4">
                  <input class="form-check-input" type="checkbox" value="" id="viewPassLogin" @click="viewPass()"/>
                  <label class="form-check-label" for="viewPassLogin"> Mostrar senha </label>
                </div>

                <button class="btn btn-lg btn-block" style="background-color:#81C784; color:white;" type="submit" :disabled="disabled">
                  <div class="spinner-border spinner-border-sm" id="spin" style="visibility: hidden; position: absolute; margin-left: -20px; margin-top:2px;" role="status">
                  </div>
                  Login
                </button>

                <hr class="my-4">
                <span>Não tem uma conta? </span>
                <a class="small" @click="formRegister" style="cursor: pointer; color: #81C784;">Cadastre-se</a>
                </form>

              </div>
            </div>

            <!-- Register -->
            <div class="card shadow-2-strong" v-if="showFormRegister" id="divRegister" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">
                <button class="btn" :disabled="disabled" @click="formRegister" style="position: absolute; top: 0; left:0; margin-top:2%; margin-left: 2%; background-color:#81C784; color:white;">
                <i class="fas fa-arrow-left"></i>
                </button>
                <h3 class="mb-5" style="color: #81C784;"><i class="far fa-comments" style="color: #81C784;"></i> Registro</h3>

                <form id="register" @submit.prevent="register">

                <div class="profile-userpic">
                  <label id="labelProfile" for="fileProfile">
                  <img src="../../public/profile.png" id="imgProfile" class="img-responsive" alt=""></label>
                  <input type="file" ref="fileInput" @change="pickFile" class="disabilitar" name="fileProfile" id="fileProfile" accept=".png, .jpeg, .jpg" required>
                </div>

                <div class="mb-4">
                  <input type="text" name="name" v-model="userRegister.name" id="name" required class="form-control form-control-lg inputRegister" placeholder="Nome" />
                </div>

                <div class="mb-4">
                  <input type="email" name="email" v-model="userRegister.email" id="email" required class="form-control form-control-lg inputRegister" placeholder="Email" />
                </div>

                <div class="mb-4">
                  <input type="password" name="pass" v-model="userRegister.password" required id="pass" class="form-control form-control-lg inputRegister" placeholder="Senha" />
                </div>

                <!-- Checkbox -->
                <div class="form-check d-flex justify-content-start mb-4">
                  <input class="form-check-input" type="checkbox" :checked="checked" value="" id="viewPass" @click="viewPassRegister()"/>
                  <label class="form-check-label" for="viewPass"> Mostrar senha </label>
                </div>

                <button class="btn btn-lg btn-block" style="background-color:#81C784; color:white;" type="submit" :disabled="disabled">
                  <div class="spinner-border spinner-border-sm" id="spin2" style="visibility: hidden; position: absolute; margin-left: -20px; margin-top:2px;" role="status">
                  </div>
                  Registrar
                </button>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    </div>
</template>

<script setup>
	import http from '@/services/https.js';
	import {reactive, ref} from 'vue';
	import { useRouter } from 'vue-router';
	import Cookie from 'js-cookie';
	
	const disabled = ref(false);
	const checked = ref(false);

	//Login
	const router = useRouter();
	const userLogin = reactive({
		email: '',
		password: ''
	});

	async function login(){

		const Toast = Swal.mixin({
		  toast: true,
		  position: 'top-end',
		  showConfirmButton: false,
		  timer: 3000,
		  timerProgressBar: true,
		  didOpen: (toast) => {
		    toast.addEventListener('mouseenter', Swal.stopTimer)
		    toast.addEventListener('mouseleave', Swal.resumeTimer)
		  }
		});

		var spin = document.getElementById('spin');
		spin.style.visibility = 'visible';

		disabled.value = true;

		try{
			const {data} = await http.post('/login',userLogin);
			if (data['var'] == 1) {
				router.push({ path: '/home' });
		
				//Save token login
				Cookie.set('login_token', data['access_token']);
				Cookie.set('idUser', data['idUser']);
			}else{
				if(data[0] == 0){
					Toast.fire({
						icon: 'error',
						text: 'Email ou senha inválida!'
					});
					
					spin.style.visibility = 'hidden';
					disabled.value = false;
				}else{
					Toast.fire({
						icon: 'error',
						text: 'Algo deu errado!'
					});
					
					spin.style.visibility = 'hidden';
					disabled.value = false;
				}
			}
		}catch(error){
			Toast.fire({
				icon: 'error',
				text: 'Erro ao tentar conexão!'
			});

			spin.style.visibility = 'hidden';
			disabled.value = false;
		}
	}

	//Register

	//Selected File Img
	const fileInput = ref(null);
	const emit = defineEmits(['input']);

	const pickFile = (event) => {
		let input = fileInput.value;
		let file = input.files;
		if(file && file[0]){
			let reader = new FileReader;
			reader.onload = e => {
				document.getElementById('imgProfile').src = e.target.result;
			}
			reader.readAsDataURL(file[0]);
			emit('input', file[0]);
		}
		userRegister.fileProfile = event.target.files[0];
	}

	const userRegister = reactive({
		fileProfile: '',
		name: '',
		email: '',
		password: '',
	});

	async function register(){
		const Toast = Swal.mixin({
		  toast: true,
		  position: 'top-end',
		  showConfirmButton: false,
		  timer: 3000,
		  timerProgressBar: true,
		  didOpen: (toast) => {
		    toast.addEventListener('mouseenter', Swal.stopTimer)
		    toast.addEventListener('mouseleave', Swal.resumeTimer)
		  }
		});

		var spin2 = document.getElementById('spin2');
		spin2.style.visibility = 'visible';

		disabled.value = true;
		try{

			const {data} = await http.post('/register',userRegister);
			
			if (data['var'] == 1) {
				Toast.fire({
					icon: 'success',
					text: 'Cadastro feito com sucesso!'
				});

				formRegister();
				document.getElementById('imgProfile').src = '/img/profile.png';
				userRegister.name = '';
				userRegister.email = '';
				userRegister.password = '';
				checked.value = false;

			}else{
				Toast.fire({
					icon: 'info',
					text: 'Existe um usuário com email já cadastrado!'
				});
			}

			spin2.style.visibility = 'hidden';
			disabled.value = false;

		}catch(error){
			Toast.fire({
				icon: 'error',
				text: 'Erro ao tentar conexão!'
			});

			spin2.style.visibility = 'hidden';
			disabled.value = false;
		}
	}


	/* Config */

	const showFormLogin = ref(true);
	const showFormRegister = ref(false);

	function viewPass(){
		var pass = document.getElementById("password");
		if (pass.type === "password") {
			pass.type = "text";
		} else {
			pass.type = "password";
		}
	}

	function viewPassRegister(){
		var pass = document.getElementById("pass");
		if (pass.type === "password") {
			pass.type = "text";
		} else {
			pass.type = "password";
		}
	}

	function formRegister(){
		if(showFormLogin.value == true){
			showFormRegister.value = true;
			showFormLogin.value = false;
		}else{
			showFormRegister.value = false;
			showFormLogin.value = true;
		}
	}
</script>

<style scoped>
	.profile-userpic img {
	  float: none;
	  margin: 0 auto;
	  object-fit: cover;
	  border-style: solid;
	  border-color: white;
	  -webkit-border-radius: 50% !important;
	  -moz-border-radius: 50% !important;
	  border-radius: 50% !important;
	}
	#labelProfile{
	  cursor: pointer;
	}
	#imgProfile{
	  width: 100px;
	  height: 100px;
	}
	#fileProfile{
	   opacity: 0;
	   position: absolute;
	   z-index: -1;
	}
</style>