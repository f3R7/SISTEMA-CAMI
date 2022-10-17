import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';
import Swal from 'sweetalert2'; 

@Component({
  selector: 'app-marcas',
  templateUrl: './marcas.component.html',
  styleUrls: ['./marcas.component.css']
})
export class MarcasComponent implements OnInit {

  @ViewChild(DataTableDirective, { static: false })
  dtElement!: DataTableDirective;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  imagenactual:string;
  nombre: string;
  marcas:any[]=[];
  url: string='http://localhost/cami/api/marcas';
  MarcaForm: FormGroup;


  constructor(private httpClient: HttpClient,  private toastr: ToastrService,  private fb: FormBuilder) {
    this.crearformulario();
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
  }

  async ngAfterViewInit() {
    await this.listar();
    this.dtTrigger.next(0);
  }

  async listar(){
    const data = (await this.httpClient.get(this.url, Auth.autorizar()).toPromise()) as any;
     this.marcas = data;
  }

  rerender() {
    this.dtElement.dtInstance.then(async (dtInstance: DataTables.Api) => {
      dtInstance.destroy();
      await this.listar();
      this.dtTrigger.next(0);
    });
  }
  reload() {
    this.rerender();
  }

  crearformulario(){
    this.MarcaForm = this.fb.group({
      IdMarca: new FormControl(),
      Nombre : new FormControl('',[Validators.required, Validators.minLength(3), Validators.maxLength(30)]),
      Pais : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(20)]),
      Ciudad : new FormControl('',[Validators.required,Validators.min(1), Validators.max(20)])
    });
  }

  verform(bandera:boolean){
    if (bandera) {
      this.MarcaForm.reset();
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
  

  guardar(){
    if(this.MarcaForm.valid){
      const idmarca = this.MarcaForm.get('IdMarca')?.value;

      if ( idmarca== null) {
    
        this.httpClient.post('http://localhost/cami/api/marcas/create' , this.MarcaForm.value, Auth.autorizar()).subscribe(result => {
          this.toastr.success('La marca fue creada exitosamente', 'Marca creada', {
            timeOut: 3000,
            positionClass: 'toast-bottom-right'
          });
          this.reload();
        this.verform(false);
      }, error => {
          this.verform(false);
        
        });
      }
        else{
  
          this.httpClient.put(`http://localhost/cami/api/marcas/update/${idmarca}`, this.MarcaForm.value, Auth.autorizar()).subscribe(r=>{
            this.reload();
            this.toastr.success('La marca fue editada exitosamente', 'Marca editada', {
              timeOut: 3000,
              positionClass: 'toast-bottom-right'
            });
            this.verform(false); 
         }, error=>{
          
          });
        } 
    }
  }

  activar(idmarca:any){
    const body = { title: 'Activar' }
 
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
            
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
      });
      
    swalWithBootstrapButtons.fire({
      title: 'Habilitar marca',
      text: "¿Quieres habilitar esta marca?",
      icon: 'warning',
      background: '#fff url(assets/img/trees.png)',
      backdrop: ` 
        rgba(4, 42, 6, 0.482)
        url("assets/img/hojas2.gif")
        left top
      
      `,
      showCancelButton: true,
      confirmButtonColor: '#2CA41A',
      confirmButtonText: 'Si, habilitar',
      cancelButtonText: 'No, cancelar',
      cancelButtonColor: '#C21616',
    }).then((result) => {
      if (result.isConfirmed) {

        this.httpClient.put<any>(`http://localhost/cami/api/marcas/activar/${idmarca}`, body, Auth.autorizar()).subscribe(d => {
          this.reload();  
         });
        swalWithBootstrapButtons.fire(
          'Habilitado',
          'Esta marca fue habilitada exitosamente',
          'success'
        )
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelado',
          'La marca no fue habilitada',
          'error'
        )
      }
    });
  
  }


  desactivar(idmarca: any) {
    
    const body= {title: 'Desactivar'}
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
          
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    });
    
  swalWithBootstrapButtons.fire({
    title: 'Deshabilitar marca',
    text: "¿Quieres deshabilitar esta marca?",
    icon: 'warning',
    background: '#fff url(assets/img/trees.png)',
    backdrop: ` 
      rgba(4, 42, 6, 0.482)
      url("assets/img/hojas2.gif")
      left top
    
    `,
    showCancelButton: true,
    confirmButtonColor: '#2CA41A',
    confirmButtonText: 'Si, Deshabilitar',
    cancelButtonText: 'No, cancelar',
    cancelButtonColor: '#C21616',
  }).then((result) => {
    if (result.isConfirmed) {

      this.httpClient.put<any>(`http://localhost/cami/api/marcas/desactivar/${idmarca}`, body, Auth.autorizar()).subscribe(d => {
        this.reload();  
       });
      swalWithBootstrapButtons.fire(
        'Deshabilitado',
        'Esta marca fue deshabilitada exitosamente',
        'success'
      )
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire(
        'Cancelado',
        'La marca no fue deshabilitada',
        'error'
      )
    }
  });
    
  }

  editar(idmarca:any){
    this.httpClient.get(`http://localhost/cami/api/marcas/edit/${idmarca}`, Auth.autorizar()).subscribe((data: any)=>{
      this.verform(true);
      this.MarcaForm.controls['IdMarca'].setValue(idmarca);
      this.MarcaForm.controls['Nombre'].setValue(data.Nombre);
      this.MarcaForm.controls['Pais'].setValue(data.Pais);
      this.MarcaForm.controls['Ciudad'].setValue(data.Ciudad);
    
    });
}

ngOnDestroy(): void {
  this.dtTrigger.unsubscribe();
}

  
}
