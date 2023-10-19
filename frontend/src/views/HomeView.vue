<template>
  <!-- Navbar -->
  <nav class="navbar navbar-light  fixed-top" style="background-color: #81C784;">
    <div class="container">
      <a class="navbar-brand text-white">
        <i class="far fa-comments fa-2x"></i> 
          <span class="h1 fw-bold mb-0" style=" font-size: 30px; margin-left: 6%; margin-top: 10px; font-family: 'Allura', cursive;">Chat</span>
      </a>

      <!-- Right elements -->
      <div class="d-flex align-items-center">
        
        <!-- Avatar -->
        <div class="dropdown">
          <a
            class="dropdown-toggle d-flex align-items-center hidden-arrow"
            href="#"
            id="navbarDropdownMenuAvatar"
            role="button"
            data-mdb-toggle="dropdown"
            aria-expanded="false"
          >
            <img id="imgProfiles" :src="imgProfile" style="object-fit: cover; border: solid 2px; border-color: white;" class="rounded-circle"
                height="35" width="35" alt="Avatar" loading="lazy" />
          </a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdownMenuAvatar"
          >
            <li>
              <a class="dropdown-item" @click="showProfile" style="cursor: pointer;">Meu Perfil</a>
            </li>
            <li>
              <a class="dropdown-item" @click="logout" style="cursor: pointer;">Sair</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Right elements -->
    </div>
  </nav>
  <!-- Navbar -->

  <div id="add-spinner"></div>
  <div id="bg-loading">
  <section style="background-color: #455A64;">
    <div class="container py-5">

      <div class="row">
        <div class="col-md-12">

          <div class="card" id="chat3" style="border-radius: 15px; width:100; margin-top: 6%;">
            <div class="card-body">

              <div class="row">
                <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                  <div class="p-3">

                    <div data-mdb-perfect-scrollbar="true" style="position: relative; height: 450px; overflow: auto;">
                      <ul class="list-unstyled mb-0" id="listUsers">
                        <!-- List Users -->
                        <div v-for="user in users.users" :key="user.id">
                        <li class="p-2 border-bottom li-bg" :id="'selected_'+user.id" style="border-radius: 3px;" v-if="user.id != idUser.idUser">
                          <a @click="showScreenMessages(user.id)" style="cursor: pointer;" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img :src="photoUrl + user.photo" alt="avatar" class="d-flex align-self-center me-3 rounded-circle" style="object-fit: cover;" width="60" height="60">
                                <span v-if="user.status == 1" class="badge badge-dot" style="background-color:#81C784; border: 1px solid white; width: 13px; height: 13px;"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0" style="color: #455A64;">{{ user.name }}</p>

                                <!-- v-for invisible -->
                                <template style="display: none;">
                                  {{ $count = 0 }}
                                  {{ $msg_empty = 0 }}
                                <label v-for="message in messages.messages" :key="message.id">
                                  <label v-if="message.user_id == idUser.idUser && message.for == user.id ||
                                  message.user_id == user.id && message.for == idUser.idUser">
                                    {{ $msg = message.message }}
                                    <label v-if="message.user_id == user.id && message.for == idUser.idUser && message.read == 1">
                                      {{ $count = $count+1 }}
                                    </label>
                                    {{ $msg_empty = $msg_empty+1 }}
                                  </label>
                                </label>
                                </template>

                                <p v-if="$msg_empty > 0" class="small text-muted text-long">{{ $msg }}</p>
                                <p v-else class="small text-muted text-long">Nenhuma mensagem</p>
                              </div>
                            </div>
                            <div v-if="$count > 0" class="pt-1">
                              <span class="badge bg-danger rounded-pill float-end">{{ $count }}</span>
                            </div>
                          </a>
                        </li>
                        </div>
                      </ul>
                    </div>

                  </div>

                </div>

                <div class="col-md-6 col-lg-7 col-xl-8 align-self-center" id="iconLogo">
                  <div style="color: #455A64;">
                    <center>
                    <i class="far fa-comments fa-9x" style="margin-right: 7px;"></i> 
                    <span class="h1 fw-bold mb-0" style=" font-size: 120px; margin-top: 10px; font-family: 'Allura', cursive;">Chat</span><br>
                    <span style="font-style: oblique;">Envie mensagens de onde e quando quiser.</span>
                    </center>
                  </div>
                </div>

                <div class="col-md-6 col-lg-7 col-xl-8" style="display: none;" id="screenMessages">
                  <!-- Messages -->
                  <div class="pt-3 pe-3 messages" ref="scrollVue" id="showMessage" data-mdb-perfect-scrollbar="true"
                    style="position: relative; height: 400px; overflow: auto;">

                    <div v-show="exists_message" v-for="message in show_messages.messages" :key="message.id">
                      <div v-if="message.user_id == idUser.idUser && message.for == forId" class="d-flex flex-row justify-content-start">
                       <img :src="imgProfile"
                         alt="avatar 1" style="width: 45px; height: 100%;" class="rounded-circle">
                        <div>
                          <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #455A64; color: white;">{{message.message}}</p>
                          <p class="small ms-3 mb-3 rounded-3 text-muted float-end">{{ moment(message.created_at).format("H:mm | DD/MM") }}</p>
                        </div>

                        <label style="display:none;">
                          {{ exists_message = true }}
                          {{ dont_exists_message = false }}
                        </label>

                      </div>
                      <div v-else-if="message.user_id == forId && message.for == idUser.idUser" class="d-flex flex-row justify-content-end">
                        <div>
                          <p class="small p-2 me-3 mb-1 text-white rounded-3 " style="background-color: #81C784;">{{ message.message }}</p>
                          <p class="small me-3 mb-3 rounded-3 text-muted"></p>
                        </div>
                        <img :src="photoUrl + forPhoto"
                         alt="avatar 1" style="width: 45px; height: 100%;" class="rounded-circle">

                        <label style="display:none;">
                          {{ exists_message = true }}
                          {{ dont_exists_message = false }}
                        </label>

                      </div>
                    </div>
                    <div v-show="dont_exists_message">
                      <center>
                        <div class="col-md-6 col-lg-7 col-xl-8" style="margin-top: 12%;">
                          <div style="color: #455A64;">
                            <center>
                            <i class="far fa-comments fa-9x" style="margin-right: 7px;"></i><br>
                            <span style="font-style: oblique;">Nenhuma mensagem!</span>
                            </center>
                          </div>
                        </div>
                      </center>
                    </div>

                  </div>

                  <div id="inputMessage" class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2" style="display: none;">
                      <img alt="Avatar Message" :src="imgProfile" style="width: 40px; height: 100%;">
                      <input type="text" class="form-control form-control-lg" id="message" @keyup="onEnterKey" placeholder="Digite a mensagem">
                      <a class="ms-4 btn" style="background-color:#455A64; color: white;" :class="{'disabled': btnDisabled}" @click="sendMessage" id="btnMessage"><i style="font-size: 15px;" class="fas fa-paper-plane"></i></a>
                  </div>
                </div>
              </div>

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
  import {reactive, ref, onMounted} from 'vue';
  import { useRouter } from 'vue-router';
  import Cookie from 'js-cookie';
  import VueScrollTo from 'vue-scrollto';
  
  import moments from 'moment'; //format date
  var moment = moments;

  //Cookie data
  const tk = Cookie.get('login_token');
  const idU = Cookie.get('idUser');
  const idS = Cookie.get('idSave');

  const token = reactive({
    token: tk
  });

  const idUser = reactive({
    idUser: idU
  });

  const router = useRouter();

  const imgProfile = ref(null);

  //Show Photo Profile
  async function showPhotoProfile(){
    const {data} = await http.post('/dataUser', idUser);
    imgProfile.value = data['photo'];  
  }

  //Show Profile
  async function showProfile(){

    document.getElementById('add-spinner').classList.add('spinner');
    document.getElementById('bg-loading').classList.add('opacity-loading');

    try{
      const {data} = await http.post('/dataUser', idUser);

      var photo = data['photo'];
      var name = data['name'];
      var email = data['email'];

      formEditProfile(photo,name,email);

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');

    }catch(error){
      Toast.fire({
        icon: 'error',
        text: 'Erro ao tentar conexão!'
      });

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');
    }

  }

  //Form Edit Profile
  function formEditProfile(photo,name,email){

    Swal.fire({
      cancelButtonText: 'Cancelar',
      showCancelButton: true,
      confirmButtonColor: '#81C784',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Salvar',
      html:
        '<div class="profile-userpic">'+
          '<label id="labelProfile" for="fileEdit">'+
          '<img src="'+photo+'" id="imgEditProfile" class="img-responsive" alt=""></label>'+
          '<input type="file" class="disabilitar" name="fileEdit" id="fileEdit" accept=".png, .jpeg, .jpg" required>'+
        '</div><br>'+
        '<input type="text" class="form-control" id="nameEdit" placeholder="Nome" value="'+name+'"><br>'+
        '<input type="text" class="form-control" id="emailEdit" placeholder="Email" value="'+email+'"><br>',
    }).then((result) => {

      if (result.isConfirmed) {
        
        if(document.getElementById('nameEdit').value == "" || document.getElementById('emailEdit').value == ""){
          Swal.fire({ 
            icon: 'info',
            text: 'Precisa preencher os campos!', 
          }).then((result) => {
            showProfile();
          });
        }else{

          var photo = document.getElementById('fileEdit').files[0];
          var name = document.getElementById('nameEdit').value;
          var email = document.getElementById('emailEdit').value;
          
          Swal.fire({
              icon: 'question',
              text: 'Salvar alterações?',
              cancelButtonText: 'Não',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sim',
          }).then((result) => {
            if (result.isConfirmed) {
              updateUser(photo, name, email);
            }
          });     
        }
      }
    });

    //Selected File Img
    document.getElementById('fileEdit').addEventListener("change", function(event) {
      event.preventDefault();
      var file = document.getElementById('fileEdit').files[0];
      var reader  = new FileReader();
      // it's onload event and you forgot (parameters)
      document.getElementById('add-spinner').classList.add('spinner');
      reader.onload = e =>  {
          document.getElementById('imgEditProfile').src = e.target.result;
          document.getElementById('add-spinner').classList.remove('spinner');
       }
       // you have to declare the file loading
       reader.readAsDataURL(file);
       userEdit.fileProfile = event.target.files[0];
    });
  }

  //Update Data User
  async function updateUser(photo,name,email){
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-start',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    let formData = new FormData();
    formData.append("photo", photo);
    formData.append("name", name);
    formData.append("email" , email);
    formData.append("idUser", idUser.idUser);

    document.getElementById('add-spinner').classList.add('spinner');
    document.getElementById('bg-loading').classList.add('opacity-loading');

    try{
      const {data} = await http.post('/updateUser', formData);

      if (data['var'] == 1) {
        Toast.fire({
          icon: 'success',
          text: 'Alteração feita!'
        });

        showPhotoProfile();
      }else{
        Toast.fire({
          icon: 'error',
          text: 'Algo deu errado!'
        });
      }

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');
    }catch(error){
      Toast.fire({
        icon: 'error',
        text: 'Erro ao tentar conexão!'
      });

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');
    }
  }

  //Show Users
  let users = reactive({users:[]});
  let messages = reactive({messages:[]});
  const photoUrl = ref(null);

  onMounted(async () => {
    
    pageRefresh();
    showPhotoProfile();

    try{

      const {data} = await http.get('/showUsers');

      document.getElementById('add-spinner').classList.add('spinner');
      document.getElementById('bg-loading').classList.add('opacity-loading');

      if(data){
        
        users.users = data[0];
        photoUrl.value = data[1]+"/";
        messages.messages = data[2];

        document.getElementById('add-spinner').classList.remove('spinner');
        document.getElementById('bg-loading').classList.remove('opacity-loading');
      }

      Cookie.remove('idSave');

    }catch(error){
      Toast.fire({
        icon: 'error',
        text: 'Erro ao tentar conexão!'
      });

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');
    }
    scrollVue.value = document.querySelector('.messages');
  });

  //Show Users Refresh
  async function showUsersRefresh(){
    const {data} = await http.get('/showUsers');

    if(data){
      
      users.users = data[0];
      photoUrl.value = data[1]+"/";
      messages.messages = data[2];

    }
  }
  
  //Show Screen Messages
  function showScreenMessages(id){
    let iconLogo = document.getElementById('iconLogo');
    let screenMessages = document.getElementById('screenMessages');
    let inputMessage = document.getElementById('inputMessage');

    var idSelected = document.getElementById("selected_"+id);
    var li_bg = document.querySelectorAll(".li-bg");

    var count = 0;

    li_bg.forEach((li)=>{
      if(li.classList.contains('bg-selected')){
        count = count+1;
      }
    });

    if (! count > 0) {
      if (!idSelected.classList.contains('bg-selected')) {
        idSelected.classList.add('bg-selected');

        document.getElementById('add-spinner').classList.add('spinner');
        document.getElementById('bg-loading').classList.add('opacity-loading');

        showMessages(id);
        document.getElementById("showMessage").html = "";
        screenMessages.style.display = 'block';
        iconLogo.style.display = 'none';
        Cookie.set('idSave', id);

        inputMessage.style.display = 'block';
      }

    }else{
      if (!idSelected.classList.contains('bg-selected')) {

        li_bg.forEach( function(li,index){
          li.classList.remove('bg-selected');
        })

        idSelected.classList.add('bg-selected');

        document.getElementById('add-spinner').classList.add('spinner');
        document.getElementById('bg-loading').classList.add('opacity-loading');

        showMessages(id);
        document.getElementById("showMessage").html = "";
        screenMessages.style.display = 'block';
        iconLogo.style.display = 'none';
        Cookie.set('idSave', id);

        inputMessage.style.display = 'block';
      }else{
        idSelected.classList.remove('bg-selected');

        screenMessages.style.display = 'none';
        iconLogo.style.display = 'block';
        Cookie.remove('idSave');

        inputMessage.style.display = 'none';
      }
    }
  }

  //Show Messages
  let show_messages = reactive({messages:[]});
  const forId = ref(null);
  const forPhoto = ref(null);
  const exists_message = ref(false);
  const dont_exists_message = ref(true);

  async function showMessages(id) {

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

    try{

      const {data} = await http.post('/showMessages', {id : id});
      
      if(data){

        forId.value = null;
        forPhoto.value = null;
        exists_message.value = false;
        dont_exists_message.value = true;
        
        show_messages.messages = data[0];
        forPhoto.value = data[1];
        forId.value = id;

        document.getElementById('add-spinner').classList.remove('spinner');
        document.getElementById('bg-loading').classList.remove('opacity-loading');
        setTimeout(()=>{
          scrollToEnd();
        });
      }

    }catch(error){

      document.getElementById('add-spinner').classList.remove('spinner');
      document.getElementById('bg-loading').classList.remove('opacity-loading');
    }
  }

  //Scroll
  const scrollVue = ref(null);

  function scrollToEnd(){
      const scrollElement = scrollVue.value.$el || scrollVue.value;

      scrollElement.scrollTop = scrollElement.scrollHeight;
  }

  //Show Messages Refresh
  async function showMessagesRefresh(id) {

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

    try{

      const {data} = await http.post('/showMessages', {id : id});

      if(data){
        
        show_messages.messages = data[0];
        forPhoto.value = data[1];
        forId.value = id;

        setTimeout(()=>{
          scrollToEnd();
        });

      }

    }catch(error){

    }
  }

  //Sends message when you click Enter
  document.addEventListener('keypress',function(e) {
    if(idS != "" && e.which === 13){
      sendMessage();
    }
  });

  //Send Message
  const btnDisabled = ref(true);
  async function sendMessage(){
    
    if (document.getElementById('message').value != "") {
      
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

      try{
        var msg = document.getElementById("message").value;
        const {data} = await http.post('/sendMessage', {
          idS : Cookie.get('idSave'), 
          msg: msg,
          idUser:idU
        });

        if(data[0] == 1){

          document.getElementById("message").value = "";
          btnDisabled.value = true;

          showMessages(Cookie.get('idSave'));
        }

      }catch(error){
        Toast.fire({
          icon: 'error',
          text: 'Erro ao tentar conexão!'
        });
      }
    }
  }

  //Refresh chat if new message arrives
  setInterval( async function() {
    const {data} = await http.get('/newMessage');

    if (data[0] == 1) {

      //List Users Refresh
      showUsersRefresh();

      //Update Refresh
      updateRefresh();
    }
  },5000);

  setInterval( async function() {
    const idSread = Cookie.get('idSave');
    if(idSread){
      const {data} = await http.post('/readMessage', {idS:idSread, idUser:idU});

      if (data[0] > 0) {

        //List Users Refresh
        showUsersRefresh();
        //Show Messages Refresh
        showMessagesRefresh(idSread);
      }
    }
  },5000);

  //Update Refresh
  async function updateRefresh() {
    const {data} = await http.post('/updateRefresh',{ idUser:idU });
  }

  //Disabled btnMessage
  function onEnterKey(){
    
    var value = document.getElementById("message").value;

    if (value != "") {
      btnDisabled.value = false;
    }else{
      btnDisabled.value = true;
    }
  }


  //Logout
  async function logout(){
    try{

      const {data} = await http.post('/logout', token);

      if(data['var'] == 1){
        Cookie.remove('login_token');
        Cookie.remove('idUser');
        router.push({ path: '/' });
      }
    }catch(error){
      Toast.fire({
        icon: 'error',
        text: 'Erro ao tentar conexão!'
      });
    }
  }

  //Updates token, when you refresh the page
  async function pageRefresh(){
    try{
      const {data} = await http.post('/checkToken', token);

      if(data['expired']){
        Cookie.set('login_token', data['newToken']);
      }
    }catch(error){
      Toast.fire({
        icon: 'error',
        text: 'Erro ao tentar conexão!'
      });
    }
  }

</script>

<style>
/* Spinner Loading */
#add-spinner{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -35px;
  margin-left: -35px;
  z-index: 9999;
}
.spinner {
  position: fixed;
  border: 8px solid rgba(0, 0, 0, 0.2);
  border-left-color: black;
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 1s linear infinite;
      
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.opacity-loading{
  opacity: 0.6;
}


/* Screen Register  */
.login-spinner{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -35px;
  margin-left: -35px;
  z-index: 9999;
}

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
#imgEditProfile{
  width: 100px;
  height: 100px;
}
#fileEdit {
   opacity: 0;
   position: absolute;
   z-index: -1;
}

#chat3 .form-control {
  border-color: transparent;
}

#chat3 .form-control:focus {
  border-color: transparent;
  box-shadow: inset 0px 0px 0px 1px transparent;
}

.badge-dot {
  border-radius: 50%;
  height: 10px;
  width: 10px;
  margin-left: 2.9rem;
  margin-top: -.75rem;
}

.bg-selected{
  background: #DCDCDC;
}

.text-long {
  width: 150px;
}

.text-long {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>
