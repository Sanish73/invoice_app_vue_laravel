//here this can be used in any where like service provider in laravel
class CustomService {
    doSomething() {
        return 'Doing something!';
    }
}

const customServiceInstance = new CustomService();
export default customServiceInstance;
