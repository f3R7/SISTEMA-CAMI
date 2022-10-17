import { HttpClient } from '@angular/common/http';
import { Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';
import { DetalleingService } from 'src/app/services/detalleing.service';

@Component({
  selector: 'app-ingresos',
  templateUrl: './ingresos.component.html',
  styleUrls: ['./ingresos.component.css']
})
export class IngresosComponent implements OnDestroy, OnInit {
  @ViewChild(DataTableDirective, { static: false })
  dtElement!: DataTableDirective;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();


  dtElementp!: DataTableDirective;

  dtOptionsp: DataTables.Settings = {};
  dtTriggerp: Subject<any> = new Subject<any>();

  ingresos:any[]=[];
  url: string = 'http://localhost/cami/api/ingresos';
  productos: any[] = [];
  urlp: string = 'http://localhost/cami/api/productos';

 

  
  constructor(public detalleserv: DetalleingService, private httpClient: HttpClient,  private toastr: ToastrService,  private fb: FormBuilder) {

  }


  ngOnInit(): void { 
    this.verform(false);
    this.dtOptions = {
      pagingType: 'full_numbers',
      pageLength: 5,
      responsive: true,

      destroy: true,
      info: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
      },
      order:[1,'desc'],
    };


    this.dtOptionsp = {
      pagingType: 'full_numbers',
      pageLength: 3,
      responsive: true,
      destroy: true,
      info: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
      },
      order:[1,'desc'],
    };


  }

  async ngAfterViewInit() {
    await this.listaringresos();
    this.dtTrigger.next(0);

    await this.listarproductos();
    this.dtTriggerp.next(0);

  }

  async listaringresos(){
    const data = (await this.httpClient.get(this.url, Auth.autorizar()).toPromise()) as any;
     this.ingresos = data;
  }

  async listarproductos(){
    const data = (await this.httpClient.get(this.urlp, Auth.autorizar()).toPromise()) as any;
     this.productos = data;
  }



  rerender() {
    this.dtElement.dtInstance.then(async (dtInstance: DataTables.Api) => {
      dtInstance.destroy();
      await this.listaringresos();
      this.dtTrigger.next(0);
    });
  }
  reload() {
    this.rerender();
  }

  
  renderproductos() {
    this.dtElementp.dtInstance.then(async (dtInstance: DataTables.Api) => {
      dtInstance.destroy();
      await this.listaringresos();
      this.dtTriggerp.next(0);
    });
  }
  reloadproductos() {
    this.renderproductos();
  }



  verform(bandera:boolean){
    if (bandera) {

     $('#form').show();
     $('#lista').hide();
     $('#btnnuevo').hide();
     
    }
    else{
     $('#form').hide();
     $('#lista').show();
     $('#btnnuevo').show();
    }
  }


  verdetalle(id: any) { 

  }


  agregar(producto: any) { 

   const product = {IdProducto: producto.IdProducto, Nombre: producto.Nombre, precio: 0}

    $('[name="btn-' + producto.IdProducto + '"]').hide();
    this.detalleserv.cart.addItem({detalles: product as any, cantidad: 1});

  }


  eliminarprod(item: any){
    this.detalleserv.cart.removeItem(item);
  }
  


  guardar() {
 
  
    const obj = {
      Fecha: $("#Fecha").val(),
      Total:  this.detalleserv.cart.getTotalValue(),
      productos: this.detalleserv.cart.productoitems
    }


    this.httpClient.post('http://localhost/cami/api/ingresos/create', obj , Auth.autorizar()).subscribe(result => {
      console.log(result);
    });
  }




  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();

    this.dtTriggerp.unsubscribe();
  }




}
