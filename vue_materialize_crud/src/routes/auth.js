import user  from '../library/mixin/user';

export default (to, from, next) => {
  
    if ( user.methods.isLoggedUser() ){
           next();
    }else{
  
          next('/login');
    }
  
  }