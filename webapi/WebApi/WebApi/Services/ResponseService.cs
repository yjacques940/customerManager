using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Rewrite.Internal.PatternSegments;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class ResponseService : BaseCrudService<Response>
    {
        public ResponseService(IWebApiContext context) : base(context)
        {
        }

        internal ActionResult<bool> HasDoneTheSurvey(int customerId)
        {
            return Context.Responses.Any(c => c.IdCustomer == customerId && c.IsActive);
        }

        internal object AddNewSurveyForAUser(ResponseInformation responses)
        {
            foreach (var response in responses.Responses)
            {
                DisableOldQuestionsIfExists(response,responses.CustomerId);
                Response newResponse = new Response
                {
                    IdCustomer = responses.CustomerId,
                    IdQuestion = response.IdQuestion
                };
                if (response.AnswerType == "bool")
                {
                    newResponse.ResponseBool = response.ResponseBool;
                }
                else
                {
                    newResponse.ResponseString = response.ResponseString;
                }
                newResponse.CreatedOn = DateConverter.CurrentEasternDateTime();
                newResponse.IsActive = true;
                Context.Add(newResponse);
            }

            Context.SaveChanges();
            return 1;
        }

        internal List<Response> GetResponsesForAUser(int customerId)
        {
            return Context.Responses.Where(c => c.IdCustomer == customerId && c.IsActive).ToList();
        }

        private void DisableOldQuestionsIfExists(ResponseFromWebInformation response, int idCustomer)
        {
            var oldQuestion =
                Context.Responses.FirstOrDefault(c => c.IdCustomer == idCustomer && c.IdQuestion == response.IdQuestion && c.IsActive);
            if (oldQuestion != null)
            {
                Remove(oldQuestion.Id);
                Context.SaveChanges();
            }
        }
    }
}
