import { productoitem } from "./productoitem";

export class Producto { 

  productoitems: productoitem[] = [];


  addItem(productoitem: productoitem){
    let found:boolean = false;
    this.productoitems = this.productoitems.map(ci => 
      {
        if(ci.detalles?.IdProducto == productoitem.detalles?.IdProducto){ 
            ci.cantidad++; 
            found = true;
        }
      return ci;
      });

    if(!found){
        this.productoitems.push(productoitem);
    }
  }

  getTotalValue():number {
    let sum = this.productoitems.reduce(
        (a, b) => {a = a + b.detalles?.precio * b.cantidad; return a;}, 0);
    return sum;
  }



  

  removeItem(item:productoitem) {
    const index = this.productoitems.indexOf(item, 0);
    if (index > -1) {
        this.productoitems.splice(index, 1);
    }
  }


}
