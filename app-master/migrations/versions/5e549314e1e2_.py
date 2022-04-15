"""empty message

Revision ID: 5e549314e1e2
Revises: 
Create Date: 2019-06-23 16:02:14.692075

"""
import sqlalchemy_utils
from alembic import op
import sqlalchemy as sa


# revision identifiers, used by Alembic.
from sqlalchemy.dialects.postgresql import ENUM

revision = '5e549314e1e2'
down_revision = None
branch_labels = None
depends_on = None


def upgrade():
    # alembic cannot handle enum for now
    enum = ENUM("free", "trial", "monthly", "yearly", name="plan_enum", create_type=False)
    enum.create(op.get_bind(), checkfirst=False)


    # ### commands auto generated by Alembic - please adjust! ###
    op.create_table('file',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('path', sa.String(length=128), nullable=False),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('path')
    )
    op.create_table('scope',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('name', sa.String(length=128), nullable=False),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('name')
    )
    op.create_table('users',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('email', sa.String(length=128), nullable=False),
    sa.Column('salt', sa.String(length=128), nullable=False),
    sa.Column('password', sa.String(length=128), nullable=False),
    sa.Column('name', sa.String(length=128), nullable=False),
    sa.Column('is_admin', sa.Boolean(), nullable=False),
    sa.Column('activated', sa.Boolean(), nullable=False),
    sa.Column('plan', enum, server_default='free', nullable=False),
    sa.Column('trial_expiration', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('stripe_customer_id', sa.String(length=128), nullable=True),
    sa.Column('stripe_card_token', sa.String(length=128), nullable=True),
    sa.Column('stripe_subscription_id', sa.String(length=128), nullable=True),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('email'),
    sa.UniqueConstraint('stripe_card_token'),
    sa.UniqueConstraint('stripe_customer_id'),
    sa.UniqueConstraint('stripe_subscription_id')
    )
    op.create_table('activation_code',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.Column('code', sa.String(length=128), nullable=False),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('code')
    )
    op.create_table('client',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('oauth_client_id', sa.String(length=128), nullable=False),
    sa.Column('oauth_client_secret', sa.String(length=128), nullable=False),
    sa.Column('name', sa.String(length=128), nullable=False),
    sa.Column('home_url', sa.String(length=1024), nullable=True),
    sa.Column('published', sa.Boolean(), nullable=False),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.Column('icon_id', sa.Integer(), nullable=True),
    sa.ForeignKeyConstraint(['icon_id'], ['file.id'], ),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('oauth_client_id')
    )
    op.create_table('gen_email',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.Column('email', sa.String(length=128), nullable=False),
    sa.Column('enabled', sa.Boolean(), nullable=False),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('email')
    )
    op.create_table('authorization_code',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('code', sa.String(length=128), nullable=False),
    sa.Column('client_id', sa.Integer(), nullable=False),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.ForeignKeyConstraint(['client_id'], ['client.id'], ondelete='cascade'),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('code')
    )
    op.create_table('client_scope',
    sa.Column('client_id', sa.Integer(), nullable=False),
    sa.Column('scope_id', sa.Integer(), nullable=False),
    sa.ForeignKeyConstraint(['client_id'], ['client.id'], ondelete='cascade'),
    sa.ForeignKeyConstraint(['scope_id'], ['scope.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('client_id', 'scope_id')
    )
    op.create_table('client_user',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.Column('client_id', sa.Integer(), nullable=False),
    sa.Column('gen_email_id', sa.Integer(), nullable=True),
    sa.ForeignKeyConstraint(['client_id'], ['client.id'], ondelete='cascade'),
    sa.ForeignKeyConstraint(['gen_email_id'], ['gen_email.id'], ondelete='cascade'),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('user_id', 'client_id', name='uq_client_user')
    )
    op.create_table('oauth_token',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('access_token', sa.String(length=128), nullable=True),
    sa.Column('client_id', sa.Integer(), nullable=False),
    sa.Column('user_id', sa.Integer(), nullable=False),
    sa.ForeignKeyConstraint(['client_id'], ['client.id'], ondelete='cascade'),
    sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id'),
    sa.UniqueConstraint('access_token')
    )
    op.create_table('redirect_uri',
    sa.Column('id', sa.Integer(), autoincrement=True, nullable=False),
    sa.Column('created_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=False),
    sa.Column('updated_at', sqlalchemy_utils.types.arrow.ArrowType(), nullable=True),
    sa.Column('client_id', sa.Integer(), nullable=False),
    sa.Column('uri', sa.String(length=1024), nullable=False),
    sa.ForeignKeyConstraint(['client_id'], ['client.id'], ondelete='cascade'),
    sa.PrimaryKeyConstraint('id')
    )
    # ### end Alembic commands ###


def downgrade():
    # ### commands auto generated by Alembic - please adjust! ###
    op.drop_table('redirect_uri')
    op.drop_table('oauth_token')
    op.drop_table('client_user')
    op.drop_table('client_scope')
    op.drop_table('authorization_code')
    op.drop_table('gen_email')
    op.drop_table('client')
    op.drop_table('activation_code')
    op.drop_table('users')
    op.drop_table('scope')
    op.drop_table('file')
    # ### end Alembic commands ###