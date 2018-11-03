function Product(id,name,price) 
{
  this.id = id;
  this.name = name;
  this.price = price;
};

function Cart()
{
  this.products = new Array();
  //this.products = [];
};

Cart.prototype.getProducts = function() 
{
    return cart.products;
};

Cart.prototype.add = function(product)
{
	this.products.push(product);
	alert(cart.toJSON());
};

Cart.prototype.remove = function(id)
{
	for(var i=0; i < this.products.count(); i++)
	{
		if(this.products == id)
			this.products.slice(id);
	}
}

Cart.prototype.toJSON = function()
{
	return JSON.stringify(this.products);
}

Cart.prototype.print = function()
{
	alert(cart.getProducts());
}



