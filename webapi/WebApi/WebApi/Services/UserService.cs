using Microsoft.AspNetCore.Mvc;
using System.Linq;
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
            var user = Context.Users.Where(c => c.Email == email &&
                    c.Password == password).First();
            if(user == null)
            {
                return null;
            }
            user.LastLogin = DateTime.Now;
            Context.SaveChanges();
            UserInformation userInfo = new UserInformation();
            userInfo.Id = user.Id;
            var customer = Context.Customers.First(c => c.Id == user.IdCustomer);
            userInfo.FullName = $"{customer.FirstName} {customer.LastName}";
            return userInfo;
        }

        public bool CheckIfUserHasPermission(int idUser, string permission)
        {
            return Context.Users.Any(u => u.Id == idUser
                && ((u.Role % 2) == 1
                || (u.Role & (Context.Permissions.FirstOrDefault(p => p.Name == permission).Bit)) != 0));
        }

        public object GetUserWithUserId(int userId, string password)
        {
            var user = Context.Users.Where(c => c.Id == userId &&
                        c.Password == password).First();
            if (user == null)
                return null;

            return user;
        }

        public object UpdateUserEmail(int userId, string email)
        {
            User user = (from c in Context.Users where c.Id == userId select c).First();
            if (user != null)
            {
                user.Email = email;
                Context.SaveChanges();
            }
            return user;
        }

        public string CheckEmailInUse(string email)
        {
            User user = (from c in Context.Users where c.Email == email select c).First();
            if (user != null)
                return user.Email;

            return "availlable";
        }
    }
}
