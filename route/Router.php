<?php
namespace route;

class Router {
    private $__routes;

    public function __construct() {
        $this->__routes = [];

        // Routes
        $this->get('/', "LoginPage");
        $this->post('/','LoginPage');
        $this->get('/register',"RegisterPage");
        $this->post('/register',"RegisterPage");
        $this->get('/dashboard',"DashBoardPage");
        // Region
        $this->get('/region',"RegionListPage");
        $this->get('/addregion',"AddRegionPage");
        $this->post('/addregion',"AddRegionPage");
        $this->get('/deletere/{id}',"DeleteRePage");
        $this->get('/editre/{id}',"EditRePage");
        $this->post('/editre',"EditRePage");
        // User
        $this->get('/users',"UserListPage");
        $this->get('/adduser',"AddUserPage");
        $this->post('/adduser',"AddUserPage");
        $this->get('/deleteuser/{id}',"DeleteUserPage");
        $this->get('/edituser/{id}',"EditUserPage");
        $this->post('/edituser',"EditUserPage");
        $this->get('/blockuser/{id}',"BlockUserPage");
        $this->get('/enableuser/{id}',"EnableUserPage");
        // Category
        $this->get('/category',"CategoryListPage");
        $this->get('/addcategory',"AddCategoryPage");
        $this->post('/addcategory',"AddCategoryPage");
        $this->get('/deletecate/{id}',"DeleteCatePage");
        $this->get('/editcate/{id}',"EditCatePage");
        $this->post('/editcate',"EditCatePage");
        // Product
        $this->get('/product',"ProductListPage");
        $this->get('/addproduct',"AddProductPage");
        $this->post('/addproduct',"AddProductPage");
        $this->get('/deletepro/{id}',"ProductDeletePage");
        $this->get('/editpro/{id}',"ProductEditPage");
        $this->post('/editpro',"ProductEditPage");
        // Order
        $this->get('/order',"OrderListPage");
        $this->get('/deleteorder/{id}',"OrderDeletePage");
        $this->get('/seeorder/{id}',"OrderSeePage");
        $this->post('/seeorder',"OrderSeePage");
        // Food
        $this->get('/northproduct',"NorthProductPage");
        $this->get('/centralproduct',"CentralProductPage");
        $this->get('/southproduct',"SouthProductPage");
        // Table
        $this->get('/table',"TableListPage");
        $this->get('/addtable',"AddTablePage");
        $this->post('/addtable',"AddTablePage");
        $this->get('/deletetable/{id}',"TableDeletePage");
        $this->get('/edittable/{id}',"TableEditPage");
        $this->post('/edittable',"TableEditPage");
        $this->get('/enabletable/{id}',"TableEnablePage");
        //Profile
        $this->get('/profile',"ProfilePage");
        $this->get('/editprofileform',"EditProfileFormPage");
        $this->post('/editprofileform',"EditProfileFormPage");
        //Handle
        $this->get('/verify/{id}',"VerifyMailPage");
        $this->post("/district","DistrictPage");
        $this->post("/commune","CommunePage");
        $this->get('/formreset',"FormResetPage");
        $this->post('/resetpassword',"ResetPasswordPage");
        $this->get('/reset/{id}',"ResetPasswordMailPage");
        $this->post('/reset',"ResetPasswordMailPage");
        $this->get('/logout',"LogoutPage");
        $this->post('/addtocart',"AddToCartPage");
        $this->get('/cart',"CartPage");
        $this->get('/cartonline',"CartOnlinePage");
        $this->get('/deletecart/{id}',"DeleteCartPage");
        $this->post('/checkout',"CheckoutPage");
        $this->get('/previousorder',"PreviousOrderPage");
        $this->post('/showproduct',"ShowProductPage");
        $this->post('/showprice',"ShowPricePage");
        $this->post('/booking',"BookingPage");
        $this->get('/return/{id}',"ReturnPage");
        $this->post('/customfilter',"CustomFilterPage");
        // Reserve
        $this->get('/singletable',"SingleTablePage");
        $this->get('/doubletable',"DoubleTablePage");
        $this->get('/othertable',"OtherTablePage");
        // Home
        $this->get('/homepage',"HomePage");
        // Refer
        $this->get('/refersouth',"ReferSouthProduct");
        $this->get('/refernorth',"ReferNorthProduct");
        $this->get('/refercentral',"ReferCentralProduct");
        // Payment
        $this->get('/payment',"PaymentPage");
        $this->post('/payment',"PaymentPage");
        // Contact
        $this->get('/contact',"ContactPage");
        $this->post('/contact',"ContactPage");
    }
    
    public function get(string $url, $action) {
        // X??? l?? ph????ng th???c GET
        $this->__request($url, 'GET', $action);
    }

    public function post(string $url, $action) {
         // X??? l?? ph????ng th???c POST
         $this->__request($url, 'POST', $action);
    }

    /**
     * 
     * X??? l?? ph????ng th???c
     * 
     * @param string $url URL c???n so kh???p
     * @param string $method method c???a route. GET ho???c POST
     * @param string|callable $action H??nh ?????ng khi URL ???????c g???i. C?? th??? l?? m???t callback ho???c m???t method trong controller
     * 
     * @return void
     * 
     */
    private function __request(string $url, string $method, $action) {
        // Kiem tra xem URL co chua param khong. VD: post/{id}
        if (preg_match_all('/({([a-zA-Z]+)})/', $url, $params)) {
            $url = preg_replace('/({([a-zA-Z]+)})/', '(.+)', $url);
        }

        // Thay the tat ca cac ki tu / bang ky tu \/ (regex) trong URL.
        $url = str_replace('/', '\/', $url);

        $route = [
            'url' => $url,
            'method' => $method,
            'action' => $action,
            'params' => $params[2]
        ];
        array_push($this->__routes, $route);
    }

    /**
     * 
     * H??m x??? l?? khi m???t URL ???????c g???i
     * 
     * @param string $url URL ???????c g???i ?????n server
     * @param string $method Ph????ng th???c url ???????c g???i. GET | POST
     * 
     * @return void
     * 
     */
    public function map(string $url, string $method) {
        // L???p qua c??c route, ki???m tra c?? ch???a url ???????c g???i kh??ng
        foreach ($this->__routes as $route) {
            // N???u route c?? $method
            if ($route['method'] == $method) {
                // Ki???m tra route hi???n t???i c?? ph???i l?? url ??ang ???????c g???i.
                $reg = '/^'.$route['url'].'$/';
                if (preg_match($reg, $url, $params)) {
                    // N???u match th?? s??? ch???y code b??n d?????i
                    array_shift($params); // Lo???i b??? r??c trong params
                    $this->__call_action_route($route['action'], $params); // Call action
                    return;
                }
            }
        }

        // N???u kh??ng kh???p v???i b???t k?? route n??o c???.
        $this->__call_action_route("NotFoundPage", []);
        return;
    }

    /**
     * 
     * H??m g???i action route
     * 
     * @param string|callable $action action c???a route
     * @param array $params C??c tham s??? tr??n url
     * 
     * @return void
     * 
     */
    private function __call_action_route($action, $params) {
        // N???u action l?? m???t view-model
        if(is_string($action)) {
            $vm_name = 'vms\\'.$action;
            $vm = new $vm_name($params);
            $vm->render();
            // Free variable after using
            $vm = null;
        }
    }
}