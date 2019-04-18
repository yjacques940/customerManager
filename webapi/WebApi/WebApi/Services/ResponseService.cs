using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Rewrite.Internal.PatternSegments;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class ResponseService : BaseCrudService<Response>
    {
        public ResponseService(IWebApiContext context) : base(context)
        {
        }

        internal ActionResult<bool> HasDoneTheSurvey(int userId)
        {
            var user = Context.Users.FirstOrDefault(c => c.Id == userId);
            if (user == null)
                return false;
            return Context.Responses.Any(c => c.IdCustomer == user.IdCustomer && c.IsActive);
        }

        internal object AddNewSurveyForAUser(ResponseInformation responses)
        {
            var customer = Context.Users.First(c => c.Id == responses.UserId);
            if (customer == null)
            {
                return null;
            }
            
            foreach (var response in responses.Responses)
            {
                DisableOldQuestionsIfExists(response,customer.IdCustomer);
                Response newResponse = new Response();
                newResponse.IdCustomer = customer.IdCustomer;
                newResponse.IdQuestion = response.IdQuestion;
                if (response.AnswerType == "bool")
                {
                    newResponse.ResponseBool = response.ResponseBool;
                }
                else
                {
                    newResponse.ResponseString = response.ResponseString;
                }
                newResponse.IsActive = true;
                Context.Add(newResponse);
            }

            Context.SaveChanges();
            return 1;
        }

        private void DisableOldQuestionsIfExists(ResponseFromWebInformation response, int idCustomer)
        {
            var oldQuestion =
                Context.Responses.FirstOrDefault(c => c.IdCustomer == idCustomer && c.IdQuestion == response.IdQuestion && c.IsActive);
            if (oldQuestion != null)
            {
                oldQuestion.IsActive = false;
                Context.Update(oldQuestion);
                Context.SaveChanges();
            }
        }
    }
}
