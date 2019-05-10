using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using System;
using System.Linq;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;

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
            UserInformation userInfo = new UserInformation();
            userInfo.IsFirstLogin = user.LastLogin == user.CreatedOn;
            user.LastLogin = DateTime.Now;
            Context.SaveChanges();
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

        internal bool IsPasswordValid(UserLoginInformation userInfo)
        {
            var user = Context.Users.FirstOrDefault(c => c.Id == userInfo.UserId && c.Password == userInfo.Password);
            return user != null;
        }

        public string CheckEmailInUse(string email)
        {
            User user = (from c in Context.Users where c.Email == email select c).First();
            if (user != null)
                return user.Email;

            return "available";
        }

        internal int SaveNewPassword(UserLoginInformation userInfo)
        {
            var user = Context.Users.FirstOrDefault(c => c.Id == userInfo.UserId);
            if (user != null)
            {
                user.Password = userInfo.Password;
                Context.Update(user);
            }
            Context.SaveChanges();
            return user.Id;
        }

        public bool SendChangePasswordEmail(IConfiguration config, string userEmail,bool isNewAccount)
        {
            var user = Context.Users.FirstOrDefault(c => c.Email == userEmail);
            if (user != null)
            {
                ActionToken actionToken = new ActionToken
                {
                    IsActive = true,
                    Action = "ForgotPassword",
                    CreatedOn = DateTime.Now,
                    ExpirationDate = DateTime.Now.AddDays(1),
                    IdUser = user.Id,
                    Token = Guid.NewGuid().ToString()
                };
                Context.ActionTokens.Add(actionToken);
                Context.SaveChanges();

                EmailSender.SendEmailToChangePassword(userEmail, actionToken.Token, config,isNewAccount);
                return true;
            }
            return false;
        }
    }
}
