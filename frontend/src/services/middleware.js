import Cookie from 'js-cookie';
import http from '@/services/https';
export default {

	//Return for screen Login if dont exists token
	async noExistsAuth(to, from, next){

		const token = Cookie.get('login_token')
		try{
			
			if(!token){
				next('/');
			}

			next();
		}catch(error){
			console.log('error');
		}
	},
	//Return for screen Home if exists token
	async existsAuth(to, from, next){

		const token = Cookie.get('login_token')
		try{
			
			if(token){

				const {data} = await http.post('/checkToken', {token: token});

				//If token expired, go screen Login and remove token
				if(data['expired']){
					Cookie.remove('login_token');
					Cookie.remove('idUser');
				}else{
					next('/home');
				}

			}

			next();
		}catch(error){
			console.log('error');
		}
	}	
	
}