import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { Producto } from '../models/producto';


@Injectable({
  providedIn: 'root'
})
export class DetalleingService {




  private readonly _cart = new BehaviorSubject<Producto>(new Producto());
  readonly cart$ = this._cart.asObservable(); 
  
  get cart(): Producto {
    return this._cart.getValue();
  }

  set cart(val: Producto) {
    this._cart.next(val);
  }


  


  constructor() { }

}
