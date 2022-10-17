import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup = new FormGroup({
    login : new FormControl('',[Validators.required]),
    clave : new FormControl('',[Validators.required])
  });
  
  constructor(private http: HttpClient, private router : Router) { }
  ngOnInit(): void {
  }

  
  async Login()
  {
    if(!this.loginForm.valid){
      return;
    }
    try{
      const token= await this.getToken() as any;
      localStorage.setItem('token-cami',token.Token);
      this.router.navigate(['roles']);
    }catch(error)
    {
      console.log(error);
    }
  }

  getToken(){
    const login = this.loginForm.get('login')?.value;
    const clave = this.loginForm.get('clave')?.value;
  
    return this.http.post(`http://localhost/cami/auth/login?Login=${login}&Clave=${clave}`, {}).toPromise();
    }

}
