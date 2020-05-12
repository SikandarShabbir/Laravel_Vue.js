import Vue from 'vue';
import router from './router'
Vue.use(router)
export default function (Vue) {

	Vue.auth = {

		setCookie(name, value) {
			var date = new Date();

			if (name == "token" && value == value) {
				date.setTime(date.getTime() + 24 * 60 * 60 * 1000 * 365); // for 1 year
			}
			document.cookie = name + "=" + value + ";path=/;expires=" + date.toGMTString();
		},

		getCookie(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') c = c.substring(1, c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
			}
			return null;
		},

		removeCookies(name) {
			document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;'
		},
		authenticationCheck(check_response) {
			//alert(check_response);
			if (check_response == 401) {
				this.removeCookies('token')
				router.push("/admin");
			}
		},
	}

	Object.defineProperties(Vue.prototype, {
		$auth: {
			get: () => {
				return Vue.auth
			}
		}
	})
}