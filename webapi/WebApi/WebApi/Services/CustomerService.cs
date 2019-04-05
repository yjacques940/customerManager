using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class CustomerService : BaseCrudService<Customer>
    {
        public CustomerService(IWebApiContext context) : base(context)
        { 
        }

        public string GetCustomerFullName(int customerId)
        {
            var customer = Context.Customers.Where(c => c.Id == customerId);
            return customer.First().FirstName + " " + customer.First().LastName;
        }

        public int ChangeCustomerLastName(int id, string lastName)
        {
            var user = Context.Customers.Where(c => c.Id == id).First();
            if (user != null)
            {
                user.LastName = lastName;
                Context.Update(user);
            }

            Context.SaveChanges();
            return user.Id;
        }

        public int AddNewCustomer(Customer customer)
        {
            //ajouter validation si le user existe déjà
            //devra créer le dernier id automatiquement,
            //aller chercher le dernier +1
            if (customer != null)
            {
                Context.Add(customer);
            }

            Context.SaveChanges();
            //Renvoyer le id du customer qui a été assigné : customer.Id
            return customer.IdAddress;
        }

        internal object ChangeCustomerEmail(int id, string email)
        {
            User user = (from c in Context.Users where c.Id == id select c).First();
            if(user != null)
            {
                user.Email = email;
                Context.SaveChanges();
            }
            return user;
        }
    }
}
