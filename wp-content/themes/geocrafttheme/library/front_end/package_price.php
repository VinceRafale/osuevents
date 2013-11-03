<script type="text/javascript">
    //getPackage() finds the price of package
    //Here, we need to take user's the selection from radio button selection
    function getPackage(){
        var package_price=0;
        //Get a reference to the form id="placeform"
        var theForm = document.forms["placeform"];
        //Get a reference to the user choose name="price_select"
        var packagePrice = theForm.elements["price_select"];
        //Here since there are 5 radio buttons getPrice.length = 4
        //We loop through each radio buttons
        for(var i=0; i < packagePrice.length; i++){
            //if the radio button is checked
            if(packagePrice[i].checked){
                //we set packagePrice to the value of the selected radio button
                package_price = packagePrice[i].value;
            }
        }
        //We return the package_price
        return package_price;
    }
    //This function finds the cost of featured list charge
    //Checkbox
    function getFeaturedH(){
        var featuredPrice=0;
        //Get a reference to the form id="placeform"
        var theForm = document.forms["placeform"];
        //Get a reference to the checkbox id="feature_h"
        var featuredCheck = theForm.elements["feature_h"];
        //If they checked the box set the featuredCheck value
        if(featuredCheck.checked==true){
            featuredPrice=featuredCheck.value;
        }        
        //Finally we return the featuredPrice
        return featuredPrice;        
    }
    //This function finds the cost for featured list category
    //Checkbox
    function getFeaturedC(){
        var featuredPriceC=0;
        //Get a recerence to the form id="placeform"
        var theForm = document.forms["placeform"];
        //Get a reference to the checkbox id="feature_c"
        var featuredCheckC = theForm.elements["feature_c"];
        //If they checked the box set the featuredCheckC value
        if(featuredCheckC.checked==true){
            featuredPriceC=featuredCheckC.value;
        }
        //Finally we return the featuredPriceC
        return featuredPriceC;
    }
    function getPrice(){
        var homeprice=0;
        var cateprice=0;
        //Get a reference to the form id="placeform"
        var theForm = document.forms["placeform"];
        //Get a reference to the user choose name="price_select"
        var h_price = theForm.elements["f_home"];
        var c_price = theForm.elements["f_cate"];
        var featured_h = theForm.elements["feature_h"];
        var featured_c = theForm.elements["feature_c"];
        var packagePrice = theForm.elements["price_select"];
        //Here since there are 5 radio buttons getPrice.length = 4
        for(var i=0; i < packagePrice.length; i++){
            if(packagePrice[i].checked==false){
                //We loop through each radio buttons
                for(var i=0; i < h_price.length; i++){
                    //if the radio button is checked
                    if(h_price[i].value !==0){
                        //we set packagePrice to the value of the selected radio button
                        homeprice = h_price[i].value;
                        featured_h.value=homeprice;
                    }
                }
                for (var i=0; i < c_price.length; i++){
                    if(c_price[i].value !==0){
                        cateprice = c_price[i].value;  
                        featured_c.value=cateprice;
                    }
                }
            }        
        }
        
        
        var fhome = document.getElementById('fhome');
        var fcat = document.getElementById('fcat');
        fhome.innerHTML=parseInt(homeprice); 
        fcat.innerHTML=parseInt(cateprice);
        //We return the package_price
        //return pkgprice; 
        
    }
    //This function calculates the total price
    function calculateTotal(){
        //Here we get the total price by calling our function
        //Each function returns the bumber so by calling them we add the values they teturn together
        var packageCost = parseInt(getFeaturedH()) + parseInt(getPackage()) + parseInt(getFeaturedC());
        //Display the result
        var resultObj = document.getElementById('result_price');
        //resultObj.style.display='block';
        resultObj.innerHTML=packageCost;
        //Get a reference to the span id="pkg_price"
        var pkgObj = document.getElementById('pkg_price');
        //Set value for package
        pkgObj.innerHTML=parseInt(getPackage()); 
        //Get a reference to the span id="feature_price"
        var featureObj = document.getElementById('feature_price');
        //Set value for featured
        featureObj.innerHTML= parseInt(getFeaturedC()) + parseInt(getFeaturedH());
        var totalPrice= document.getElementById('total_price');
        totalPrice.value=parseInt(getFeaturedC()) + parseInt(getFeaturedH())+parseInt(getPackage());
        getPrice();
    }
</script>
