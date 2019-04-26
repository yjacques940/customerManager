using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.ChangeTracking;
using WebApi.Models;

namespace WebApi.Data
{
    public interface IWebApiContext
    {
        DbSet<Customer> Customers { get; set; }
        DbSet<Appointment> Appointments { get; set; }
        DbSet<PhoneNumber> PhoneNumbers { get; set; }
        DbSet<User> Users { get; set; }
        DbSet<PhoneType> PhoneTypes { get; set; }
        DbSet<Permission> Permissions { get; set; }
        DbSet<Address> Addresses { get; set; }
        DbSet<State> States { get; set; }
        DbSet<TimeSlot> TimeSlots { get; set; }
        DbSet<ActionToken> ActionTokens { get; set; }
        DbSet<Question> Questions { get; set; }
        DbSet<Response> Responses { get; set; }

        DbSet<TEntity> Set<TEntity>() where TEntity : class;
        EntityEntry<TEntity> Add<TEntity>(TEntity entity) where TEntity : class;
        EntityEntry Update(object entity);
        EntityEntry Remove(object entity);
        void RemoveRange(params object[] entities);
        TEntity Find<TEntity>(params object[] keyValues) where TEntity : class;
        int SaveChanges();
        EntityEntry<TEntity> Entry<TEntity>(TEntity entity) where TEntity : class;
    }
}
