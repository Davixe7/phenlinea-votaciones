import { userService } from '../_services';
import { router } from '../_helpers';

const user = JSON.parse( localStorage.getItem('user') );
const initialState = user
? { status: { loggedIn: true }, user }
: { status: {}, user: null };

export const authentication = {
  namespaced: true,
  state: initialState,
  actions: {
    login({ dispatch, commit }, { email, password }) {
      commit('loginRequest', { email });
      
      userService.login(email, password)
      .then(
        user => {
          commit('loginSuccess', user);
          if( user.role === 'marine' ){
            router.push('/profile');
          }else{
            router.push('/marines');
          }
          
        },
        error => {
          commit('loginFailure', error);
          dispatch('alert/error', error, { root: true });
        }
      );
    },
    logout({ commit }) {
      userService.logout();
      router.replace('/login')
      commit('logout');
    }
  },
  mutations: {
    loginRequest(state, user) {
      state.status = { loggingIn: true };
      state.user = user;
    },
    loginSuccess(state, user) {
      state.status = { loggedIn: true };
      state.user = user;
    },
    loginFailure(state) {
      state.status = {};
      state.user = null;
    },
    logout(state) {
      state.status = {};
      state.user = null;
    }
  }
}