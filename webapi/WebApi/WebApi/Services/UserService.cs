using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class UserService : BaseCrudService<User>
    {
        public UserService(IWebApiContext context) : base(context)
        {
        }

        public ActionResult<UserInformation> GetUserInformation(string email, string password)
        {
            var user = Context.Users.Where(c => c.Email== email &&
                    c.Password == password).First();

            if(user == null)
            {
                return null;
            }
            UserInformation userInfo = new UserInformation();
            userInfo.Id = user.Id;
            var customer = Context.Customers.First(c => c.Id == user.IdCustomer);
            userInfo.FullName = $"{customer.FirstName} {customer.LastName}";
            return userInfo;
        }

        public object GetUserWithUserId(int userid, string password)
        {
            var user = Context.Users.Where(c => c.Id == userid &&
                        c.Password == password).First();
            if (user == null)
                return null;

            return user;
        }

        public object ChangeUserEmail(int userid, string email)
        {
            User user = (from c in Context.Users where c.Id == userid select c).First();
            if (user != null)
            {
                user.Email = email;
                Context.SaveChanges();
            }
            return user;
        }
    }
}
